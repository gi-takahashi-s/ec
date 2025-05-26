@extends('layouts.admin')

@section('header', 'ダッシュボード')

@section('content')
    <!-- 概要カード -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 総売上 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総売上
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    ¥{{ number_format($totalSales) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        今日: ¥{{ number_format($todaySales) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 注文数 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総注文数
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ number_format($totalOrders) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        未処理: {{ $pendingOrders }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 商品数 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総商品数
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ number_format($totalProducts) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        在庫切れ: {{ $outOfStockProducts }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ユーザー数 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総ユーザー数
                            </dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ number_format($totalUsers) }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-gray-500 dark:text-gray-400">
                        今日の新規: {{ $newUsersToday }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- 注文とグラフ -->
    <div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
        <!-- 最近の注文 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    最近の注文
                </h3>
            </div>
            <div class="overflow-hidden">
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentOrders as $order)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="truncate">
                                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                                注文番号 #{{ $order->order_number }}
                                            </a>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->user->name }} - ¥{{ number_format($order->total) }}
                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->order_status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($order->order_status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                            {{ ucfirst($order->order_status) }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-4 sm:px-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400">注文がありません</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-4 sm:px-6 rounded-b-lg">
                <div class="text-sm">
                    <a href="{{ route('admin.orders.index') }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                        すべての注文を表示<span aria-hidden="true"> &rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 売上グラフ -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    最近の売上推移（過去12日間）
                </h3>
            </div>
            <div class="p-4">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- 注文ステータス -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    注文ステータス概要
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">未処理</p>
                        <p class="mt-1 text-3xl font-semibold text-yellow-500">{{ $pendingOrders }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">処理中</p>
                        <p class="mt-1 text-3xl font-semibold text-blue-500">{{ $processingOrders }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">完了</p>
                        <p class="mt-1 text-3xl font-semibold text-green-500">{{ $completedOrders }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">キャンセル</p>
                        <p class="mt-1 text-3xl font-semibold text-red-500">{{ $cancelledOrders }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // 売上データの準備
        const salesData = {!! json_encode($salesData) !!};
        const labels = salesData.map(item => item.date);
        const data = salesData.map(item => item.sales);
        
        // グラフの描画
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '売上（円）',
                    data: data,
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '¥' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '売上: ¥' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection 