<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * 管理者ダッシュボードを表示
     */
    public function index()
    {
        // 売上概要データを取得
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $todaySales = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total');
        $monthSales = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // 注文概要データを取得
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $completedOrders = Order::where('order_status', 'completed')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();
        
        // 商品概要データを取得
        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();
        
        // ユーザー概要データを取得
        $totalUsers = User::where('is_admin', false)->count();
        $newUsersToday = User::where('is_admin', false)
            ->whereDate('created_at', today())
            ->count();
        
        // 最近の注文を取得
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // 売上グラフ用データ（過去12日間）
        $salesData = $this->getSalesData();
        
        return view('admin.dashboard', compact(
            'totalSales', 'todaySales', 'monthSales',
            'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders',
            'totalProducts', 'outOfStockProducts', 'lowStockProducts',
            'totalUsers', 'newUsersToday',
            'recentOrders', 'salesData'
        ));
    }
    
    /**
     * 売上グラフ用データを取得（過去12日間）
     */
    private function getSalesData()
    {
        $salesData = [];
        
        // 過去12日間の日付を取得
        $dates = collect();
        for ($i = 11; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }
        
        // 各日の売上を取得
        $sales = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', now()->subDays(11))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->total;
            })
            ->toArray();
        
        // 日付ごとに売上を整形
        foreach ($dates as $date) {
            $salesData[] = [
                'date' => $date,
                'sales' => $sales[$date] ?? 0,
            ];
        }
        
        return $salesData;
    }
}
