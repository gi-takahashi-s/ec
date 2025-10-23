<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BankTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * 注文一覧を表示
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'bankTransfer']);
        
        // 検索フィルタリング
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('total', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // 注文ステータスフィルタリング
        if ($request->has('order_status') && $request->input('order_status') != '') {
            $query->where('order_status', $request->input('order_status'));
        }
        
        // 支払いステータスフィルタリング
        if ($request->has('payment_status') && $request->input('payment_status') != '') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // 支払い方法フィルタリング
        if ($request->has('payment_method') && $request->input('payment_method') != '') {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // 銀行振込ステータスフィルタリング
        if ($request->has('bank_transfer_status') && $request->input('bank_transfer_status') != '') {
            $query->whereHas('bankTransfer', function($q) use ($request) {
                $q->where('transfer_status', $request->input('bank_transfer_status'));
            });
        }
        
        // 日付範囲フィルタリング
        if ($request->has('start_date') && $request->input('start_date') != '') {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        
        if ($request->has('end_date') && $request->input('end_date') != '') {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        
        // 並び替え
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $orders = $query->paginate(15);
        
        // 注文ステータスの選択肢
        $orderStatuses = [
            'pending' => '未処理',
            'processing' => '処理中',
            'shipped' => '発送済み',
            'delivered' => '配達済み',
            'completed' => '完了',
            'cancelled' => 'キャンセル',
        ];
        
        // 支払いステータスの選択肢
        $paymentStatuses = [
            'pending' => '未決済',
            'paid' => '決済済み',
            'failed' => '決済失敗',
            'refunded' => '返金済み',
        ];

        // 支払い方法の選択肢
        $paymentMethods = [
            'stripe' => 'クレジットカード',
            'bank_transfer' => '銀行振込',
            'cash_on_delivery' => '代金引換',
        ];

        // 銀行振込ステータスの選択肢
        $bankTransferStatuses = [
            'pending' => '振込待ち',
            'confirmed' => '確認済み',
            'expired' => '期限切れ',
        ];

        // 銀行振込統計情報
        $bankTransferStats = [
            'pending' => BankTransfer::where('transfer_status', 'pending')->count(),
            'confirmed' => BankTransfer::where('transfer_status', 'confirmed')->count(),
            'expired' => BankTransfer::where('transfer_status', 'expired')->count(),
            'total_amount_pending' => BankTransfer::where('transfer_status', 'pending')->sum('transfer_amount'),
        ];
        
        return view('admin.orders.index', compact(
            'orders', 
            'orderStatuses', 
            'paymentStatuses',
            'paymentMethods',
            'bankTransferStatuses',
            'bankTransferStats'
        ));
    }

    /**
     * 注文詳細を表示
     */
    public function show(Order $order)
    {
        $order->load([
            'user', 
            'shippingAddress', 
            'items.product.mainImage',
            'bankTransfer.confirmedBy'
        ]);
        
        // 注文ステータスの選択肢
        $orderStatuses = [
            'pending' => '未処理',
            'processing' => '処理中',
            'shipped' => '発送済み',
            'delivered' => '配達済み',
            'completed' => '完了',
            'cancelled' => 'キャンセル',
        ];
        
        return view('admin.orders.show', compact('order', 'orderStatuses'));
    }

    /**
     * 注文ステータスを更新
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:500',
            'tracking_number' => 'nullable|string|max:255',
            'shipping_method' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable|string|max:255',
            'shipping_memo' => 'nullable|string|max:1000',
        ]);
        
        $oldStatus = $order->order_status;
        $newStatus = $request->input('order_status');
        
        // 注文ステータスの更新
        $order->order_status = $newStatus;
        
        // ステータスに応じて日時を更新
        if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
            $order->shipped_at = now();
        } elseif ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $order->delivered_at = now();
        }
        
        // 備考の更新
        if ($request->has('notes')) {
            $order->notes = $request->input('notes');
        }
        
        // 配送情報の更新
        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->input('tracking_number');
        }
        if ($request->has('shipping_method')) {
            $order->shipping_method = $request->input('shipping_method');
        }
        if ($request->has('delivery_date')) {
            $order->delivery_date = $request->input('delivery_date');
        }
        if ($request->has('delivery_time')) {
            $order->delivery_time = $request->input('delivery_time');
        }
        if ($request->has('shipping_memo')) {
            $order->shipping_memo = $request->input('shipping_memo');
        }
        
        $order->save();
        
        // 発送済みに変更された場合、発送通知メールを送信
        if ($newStatus === 'shipped' && $oldStatus !== 'shipped' && $order->user) {
            try {
                $order->user->notify(new \App\Notifications\ShippingNotification($order));
            } catch (\Exception $e) {
                // メール送信エラーをログに記録（処理は継続）
                \Log::error('発送通知メール送信エラー: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'user_id' => $order->user->id,
                ]);
            }
        }
        
        // リダイレクト先を判定（一覧画面からの呼び出しかどうか）
        $redirectTo = $request->input('redirect_to', 'show');
        
        if ($redirectTo === 'index') {
            return redirect()->route('admin.orders.index')
                ->with('success', '注文ステータスを更新しました。');
        }
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', '注文ステータスを更新しました。');
    }

    /**
     * 銀行振込を確認済みにする
     */
    public function confirmBankTransfer(Request $request, Order $order)
    {
        if (!$order->bankTransfer) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'この注文には銀行振込情報がありません。');
        }

        if ($order->bankTransfer->transfer_status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'この銀行振込は既に処理済みです。');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // 銀行振込を確認済みに更新
            $order->bankTransfer->update([
                'transfer_status' => 'confirmed',
                'transfer_confirmed_at' => now(),
                'confirmed_by' => Auth::id(),
                'admin_notes' => $request->input('admin_notes'),
            ]);

            // 注文の支払いステータスを更新
            $order->update([
                'payment_status' => 'paid',
            ]);

            DB::commit();

            // リダイレクト先を判定（一覧画面からの呼び出しかどうか）
            $redirectTo = $request->input('redirect_to', 'show');
            
            if ($redirectTo === 'index') {
                return redirect()->route('admin.orders.index')
                    ->with('success', '銀行振込を確認済みにしました。');
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', '銀行振込を確認済みにしました。');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // エラー時のリダイレクト先も判定
            $redirectTo = $request->input('redirect_to', 'show');
            
            if ($redirectTo === 'index') {
                return redirect()->route('admin.orders.index')
                    ->with('error', '銀行振込の確認処理に失敗しました: ' . $e->getMessage());
            }
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', '銀行振込の確認処理に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * 銀行振込を期限切れにする
     */
    public function markBankTransferExpired(Order $order)
    {
        if (!$order->bankTransfer) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'この注文には銀行振込情報がありません。');
        }

        if ($order->bankTransfer->transfer_status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'この銀行振込は既に処理済みです。');
        }

        try {
            DB::beginTransaction();

            // 銀行振込を期限切れに更新
            $order->bankTransfer->update([
                'transfer_status' => 'expired',
            ]);

            // 注文をキャンセルに更新
            $order->update([
                'order_status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order)
                ->with('success', '銀行振込を期限切れにし、注文をキャンセルしました。');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', '期限切れ処理に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * 注文を削除（通常は使用しないが、テスト用など特殊なケース用）
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // 銀行振込情報の削除
            if ($order->bankTransfer) {
                $order->bankTransfer->delete();
            }

            // 注文アイテムの削除
            $order->items()->delete();
            
            // 注文の削除
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', '注文を削除しました。');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.orders.index')
                ->with('error', '注文の削除に失敗しました: ' . $e->getMessage());
        }
    }
}
