<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * 売上レポートを表示
     */
    public function sales(Request $request)
    {
        // 期間の取得（デフォルトは過去30日間）
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->subDays(30);
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();
        
        // 期間が30日以上の場合は日単位、それ以外は日単位で集計
        $isMonthly = $startDate->diffInDays($endDate) >= 30;
        
        // 売上データの取得
        $salesData = $this->getSalesData($startDate, $endDate, $isMonthly);
        
        // 売上概要データの取得
        $summary = $this->getSalesSummary($startDate, $endDate);
        
        // 支払い方法別の集計
        $paymentMethodStats = $this->getPaymentMethodStats($startDate, $endDate);
        
        return view('admin.reports.sales', compact(
            'salesData',
            'summary',
            'paymentMethodStats',
            'startDate',
            'endDate',
            'isMonthly'
        ));
    }

    /**
     * 商品レポートを表示
     */
    public function products(Request $request)
    {
        // 期間の取得（デフォルトは過去30日間）
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->subDays(30);
        
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();
        
        // 商品別売上データの取得
        $topProducts = $this->getTopProducts($startDate, $endDate, 10);
        
        // カテゴリ別売上データの取得
        $categoryStats = $this->getCategoryStats($startDate, $endDate);
        
        // 在庫状況の取得
        $stockStatus = $this->getStockStatus();
        
        return view('admin.reports.products', compact(
            'topProducts',
            'categoryStats',
            'stockStatus',
            'startDate',
            'endDate'
        ));
    }

    /**
     * 売上データを取得
     */
    private function getSalesData($startDate, $endDate, $isMonthly = false)
    {
        if ($isMonthly) {
            // 月単位での集計
            return Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            // 日単位での集計
            return Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(
                    DB::raw('DATE(created_at) as period'),
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }
    }

    /**
     * 売上概要データを取得
     */
    private function getSalesSummary($startDate, $endDate)
    {
        // 対象期間の売上合計
        $totalSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');
        
        // 対象期間の注文数
        $totalOrders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // 平均注文金額
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // 前期間との比較（前期間は同じ長さの期間）
        $periodLength = $startDate->diffInDays($endDate);
        $previousStart = (clone $startDate)->subDays($periodLength);
        $previousEnd = (clone $endDate)->subDays($periodLength);
        
        $previousSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total');
        
        $salesGrowth = $previousSales > 0 
            ? (($totalSales - $previousSales) / $previousSales) * 100 
            : 0;
        
        return [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'sales_growth' => $salesGrowth,
            'previous_sales' => $previousSales,
            'previous_start' => $previousStart,
            'previous_end' => $previousEnd,
        ];
    }

    /**
     * 支払い方法別の集計を取得
     */
    private function getPaymentMethodStats($startDate, $endDate)
    {
        return Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * トップ商品を取得
     */
    private function getTopProducts($startDate, $endDate, $limit = 10)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * カテゴリ別売上データを取得
     */
    private function getCategoryStats($startDate, $endDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_sales')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();
    }

    /**
     * 在庫状況を取得
     */
    private function getStockStatus()
    {
        return [
            'total' => Product::count(),
            'in_stock' => Product::where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
        ];
    }
}
