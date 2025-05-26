<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('orders.index', compact('orders'));
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

        return view('orders.show', compact('order'));
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

        $pdf = PDF::loadView('orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
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

        $pdf = PDF::loadView('orders.receipt', compact('order'));
        
        return $pdf->download('receipt-' . $order->order_number . '.pdf');
    }
}
