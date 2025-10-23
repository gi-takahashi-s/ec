<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 注文履歴一覧を表示
     */
    public function index()
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->get();
        
        // ステータスの日本語表示用配列
        $orderStatuses = [
            'pending' => '未処理',
            'processing' => '処理中',
            'shipped' => '発送済み',
            'delivered' => '配達済み',
            'completed' => '完了',
            'cancelled' => 'キャンセル',
        ];
        
        $paymentStatuses = [
            'pending' => '未払い',
            'paid' => '支払い済み',
            'failed' => '失敗',
            'refunded' => '返金済み',
        ];
        
        $paymentMethods = [
            'stripe' => 'クレジットカード',
            'bank_transfer' => '銀行振込',
            'cash_on_delivery' => '代金引換',
        ];
        
        $bankTransferStatuses = [
            'pending' => '振込待ち',
            'confirmed' => '確認済み',
            'expired' => '期限切れ',
        ];
        
        return view('orders.index', compact('orders', 'orderStatuses', 'paymentStatuses', 'paymentMethods', 'bankTransferStatuses'));
    }

    /**
     * 注文詳細を表示
     */
    public function show(Order $order)
    {
        // 自分の注文のみ表示可能
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // 必要なリレーションをロード
        $order->load(['bankTransfer', 'items.product', 'shippingAddress']);

        // ステータスの日本語表示用配列
        $orderStatuses = [
            'pending' => '未処理',
            'processing' => '処理中',
            'shipped' => '発送済み',
            'delivered' => '配達済み',
            'completed' => '完了',
            'cancelled' => 'キャンセル',
        ];
        
        $paymentStatuses = [
            'pending' => '未払い',
            'paid' => '支払い済み',
            'failed' => '失敗',
            'refunded' => '返金済み',
        ];
        
        $paymentMethods = [
            'stripe' => 'クレジットカード',
            'bank_transfer' => '銀行振込',
            'cash_on_delivery' => '代金引換',
        ];
        
        $bankTransferStatuses = [
            'pending' => '振込待ち',
            'confirmed' => '確認済み',
            'expired' => '期限切れ',
        ];

        return view('orders.show', compact('order', 'orderStatuses', 'paymentStatuses', 'paymentMethods', 'bankTransferStatuses'));
    }

    /**
     * 注文をキャンセル
     */
    public function cancel(Order $order)
    {
        // 自分の注文のみキャンセル可能
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // 未発送の注文のみキャンセル可能
        if ($order->order_status !== 'pending' && $order->order_status !== 'processing') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'この注文はキャンセルできません。');
        }

        $order->order_status = 'cancelled';
        $order->save();

        return redirect()->route('orders.show', $order)
            ->with('success', '注文をキャンセルしました。');
    }

    /**
     * 請求書をダウンロード
     */
    public function downloadInvoice(Order $order)
    {
        // 自分の注文のみダウンロード可能
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            Log::info('請求書PDF生成開始', ['order_id' => $order->id]);
            
            // 必要なリレーションをロード
            $order->load(['items.product', 'shippingAddress']);
            
            Log::info('DOMPDFの設定確認', [
                'default_font' => config('dompdf.options.default_font'),
                'font_dir' => config('dompdf.options.font_dir'),
                'enable_font_subsetting' => config('dompdf.options.enable_font_subsetting')
            ]);
            
            // フォントファイルの存在確認
            $fontPath = storage_path('fonts/ipag.ttf');
            Log::info('フォントファイル確認', [
                'font_path' => $fontPath,
                'exists' => file_exists($fontPath),
                'readable' => is_readable($fontPath)
            ]);
            
            // 文字コードをUTF-8に統一（記事の推奨事項）
            $orderData = $this->convertToUtf8($order->toArray());
            
            $pdf = PDF::loadView('orders.invoice', ['order' => $order])
                        ->set_option('compress', 1)
                        ->set_option('defaultFont', 'NotoSansJP')
                        ->setPaper('a4', 'portrait'); // 縦A4サイズに指定
            
            Log::info('請求書PDF生成成功', ['order_id' => $order->id]);
            return $pdf->download('invoice-' . $order->order_number . '.pdf');
            
        } catch (\Exception $e) {
            // エラーが出たらログに残しておく
            Log::error('請求書PDFでエラーが発生しました', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('orders.show', $order)
                ->with('error', '請求書の生成に失敗しました。詳細: ' . $e->getMessage());
        }
    }

    /**
     * 領収書をダウンロード（支払い済みの場合のみ）
     */
    public function downloadReceipt(Order $order)
    {
        // 自分の注文のみダウンロード可能
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // 支払い済みの注文のみ領収書を発行可能
        if ($order->payment_status !== 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', '支払いが完了していないため、領収書を発行できません。');
        }

        try {
            Log::info('領収書PDF生成開始', ['order_id' => $order->id]);
            
            // 必要なリレーションをロード
            $order->load(['items.product', 'shippingAddress']);
            
            // 文字コードをUTF-8に統一（記事の推奨事項）
            $orderData = $this->convertToUtf8($order->toArray());
            
            $pdf = PDF::loadView('orders.receipt', ['order' => $order])
                        ->set_option('compress', 1)
                        ->set_option('defaultFont', 'NotoSansJP')
                        ->setPaper('a4', 'portrait'); // 縦A4サイズに指定
            
            Log::info('領収書PDF生成成功', ['order_id' => $order->id]);
            return $pdf->download('receipt-' . $order->order_number . '.pdf');
            
        } catch (\Exception $e) {
            // エラーが出たらログに残しておく
            Log::error('領収書PDFでエラーが発生しました', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('orders.show', $order)
                ->with('error', '領収書の生成に失敗しました。詳細: ' . $e->getMessage());
        }
    }

    /**
     * 文字コードをUTF-8に統一する（記事の推奨事項）
     */
    private function convertToUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->convertToUtf8($value);
            }
        } elseif (is_string($data)) {
            return mb_convert_encoding(
                $data,
                'UTF-8',
                'ASCII,JIS,UTF-8,EUC-JP,SJIS'
            );
        }
        
        return $data;
    }
}
