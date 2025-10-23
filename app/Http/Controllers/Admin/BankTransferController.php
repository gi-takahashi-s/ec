<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankTransferController extends Controller
{
    /**
     * 銀行振込一覧を表示
     */
    public function index(Request $request)
    {
        $query = BankTransfer::with(['order.user', 'confirmedBy']);

        // ステータスフィルター
        if ($request->filled('status')) {
            $query->where('transfer_status', $request->status);
        }

        // 期限フィルター
        if ($request->filled('deadline_filter')) {
            switch ($request->deadline_filter) {
                case 'expired':
                    $query->where('transfer_deadline', '<', Carbon::now())
                          ->where('transfer_status', BankTransfer::STATUS_PENDING);
                    break;
                case 'today':
                    $query->whereDate('transfer_deadline', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('transfer_deadline', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
            }
        }

        // 検索
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bankTransfers = $query->orderBy('created_at', 'desc')->paginate(20);

        // 統計情報
        $stats = [
            'pending' => BankTransfer::pending()->count(),
            'confirmed' => BankTransfer::confirmed()->count(),
            'expired' => BankTransfer::expired()->count(),
            'total_amount_pending' => BankTransfer::pending()->sum('transfer_amount'),
        ];

        return view('admin.bank-transfers.index', compact('bankTransfers', 'stats'));
    }

    /**
     * 振込詳細を表示
     */
    public function show(BankTransfer $bankTransfer)
    {
        $bankTransfer->load(['order.user', 'order.items.product', 'confirmedBy']);
        
        return view('admin.bank-transfers.show', compact('bankTransfer'));
    }

    /**
     * 振込確認処理
     */
    public function confirm(Request $request, BankTransfer $bankTransfer)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($bankTransfer->transfer_status === BankTransfer::STATUS_CONFIRMED) {
            return redirect()->back()->with('error', '既に確認済みです。');
        }

        DB::beginTransaction();
        try {
            // 振込確認
            $bankTransfer->confirm(Auth::user(), $request->admin_notes);

            // 注文ステータスを更新
            $order = $bankTransfer->order;
            $order->payment_status = 'paid';
            $order->paid_at = Carbon::now();
            $order->transfer_confirmed_at = Carbon::now();
            $order->order_status = 'processing';
            $order->save();

            DB::commit();

            return redirect()->route('admin.bank-transfers.show', $bankTransfer)
                           ->with('success', '振込確認が完了しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '振込確認処理中にエラーが発生しました。');
        }
    }

    /**
     * 期限切れ処理
     */
    public function markExpired(BankTransfer $bankTransfer)
    {
        if ($bankTransfer->transfer_status !== BankTransfer::STATUS_PENDING) {
            return redirect()->back()->with('error', '処理できないステータスです。');
        }

        DB::beginTransaction();
        try {
            $bankTransfer->markAsExpired();

            // 注文ステータスを更新
            $order = $bankTransfer->order;
            $order->order_status = 'cancelled';
            $order->save();

            DB::commit();

            return redirect()->route('admin.bank-transfers.show', $bankTransfer)
                           ->with('success', '期限切れ処理が完了しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '期限切れ処理中にエラーが発生しました。');
        }
    }

    /**
     * 一括期限切れ処理
     */
    public function bulkMarkExpired()
    {
        $expiredTransfers = BankTransfer::expired()->get();
        
        DB::beginTransaction();
        try {
            foreach ($expiredTransfers as $transfer) {
                $transfer->markAsExpired();
                
                // 注文ステータスを更新
                $order = $transfer->order;
                $order->order_status = 'cancelled';
                $order->save();
            }

            DB::commit();

            return redirect()->route('admin.bank-transfers.index')
                           ->with('success', "{$expiredTransfers->count()}件の期限切れ処理が完了しました。");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '一括期限切れ処理中にエラーが発生しました。');
        }
    }
}
