<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = Order::with(['user', 'shippingAddress']);
        
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
        
        return view('admin.orders.index', compact(
            'orders', 
            'orderStatuses', 
            'paymentStatuses'
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
            'items.product.mainImage'
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
        
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', '注文ステータスを更新しました。');
    }

    /**
     * 注文を削除（通常は使用しないが、テスト用など特殊なケース用）
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
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
