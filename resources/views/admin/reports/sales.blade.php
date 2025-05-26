@extends('layouts.admin')

@section('header', '売上レポート')

@section('content')
    <!-- 期間選択 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-5">
                    <!-- 開始日 -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">開始日</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <!-- 終了日 -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">終了日</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <!-- 集計単位 -->
                    <div>
                        <label for="is_monthly" class="block text-sm font-medium text-gray-700 dark:text-gray-300">集計単位</label>
                        <select name="is_monthly" id="is_monthly" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0" {{ !$isMonthly ? 'selected' : '' }}>日別</option>
                            <option value="1" {{ $isMonthly ? 'selected' : '' }}>月別</option>
                        </select>
                    </div>
                    
                    <!-- 期間ショートカット -->
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">期間</label>
                        <select id="period" onchange="setPeriod(this.value)" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">選択してください</option>
                            <option value="today">今日</option>
                            <option value="yesterday">昨日</option>
                            <option value="7days">過去7日間</option>
                            <option value="30days">過去30日間</option>
                            <option value="this_month">今月</option>
                            <option value="last_month">先月</option>
                            <option value="this_year">今年</option>
                            <option value="last_year">昨年</option>
                        </select>
                    </div>
                    
                    <!-- 検索ボタン -->
                    <div class="flex items-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            レポート生成
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- 売上サマリー -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                売上概要（{{ $startDate->format('Y/m/d') }} 〜 {{ $endDate->format('Y/m/d') }}）
            </h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- 総売上 -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総売上
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                ¥{{ number_format($summary['total_sales']) }}
                            </dd>
                            <dd class="mt-2 text-sm font-medium text-{{ $summary['sales_growth'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $summary['sales_growth'] >= 0 ? 'green' : 'red' }}-400">
                                {{ $summary['sales_growth'] >= 0 ? '+' : '' }}{{ number_format($summary['sales_growth'], 1) }}%
                                <span class="text-gray-500 dark:text-gray-400">前期間比</span>
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 注文数 -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                注文数
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($summary['total_orders']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $summary['previous_start']->format('Y/m/d') }} 〜 {{ $summary['previous_end']->format('Y/m/d') }}
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 平均注文金額 -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                平均注文金額
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                ¥{{ number_format($summary['average_order_value']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                総売上 ÷ 注文数
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 前期間売上 -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                前期間売上
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                ¥{{ number_format($summary['previous_sales']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $summary['previous_start']->format('Y/m/d') }} 〜 {{ $summary['previous_end']->format('Y/m/d') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 売上グラフ -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                売上推移
            </h3>
            <div class="h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- 支払い方法別集計 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                支払い方法別集計
            </h3>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- グラフ -->
                <div class="h-64">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
                
                <!-- テーブル -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    支払い方法
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    注文数
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    売上
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    割合
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($paymentMethodStats as $stat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $stat->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($stat->count) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                        ¥{{ number_format($stat->total) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ number_format(($stat->total / $summary['total_sales']) * 100, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 売上詳細テーブル -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                売上詳細
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                期間
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                注文数
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                売上
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                平均注文金額
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($salesData as $data)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $isMonthly ? $data->period : \Carbon\Carbon::parse($data->period)->format('Y/m/d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($data->order_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    ¥{{ number_format($data->total_sales) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                    ¥{{ number_format($data->order_count > 0 ? $data->total_sales / $data->order_count : 0) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 期間選択ヘルパー
    function setPeriod(value) {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const today = new Date();
        
        switch(value) {
            case 'today':
                startDateInput.value = formatDate(today);
                endDateInput.value = formatDate(today);
                break;
            case 'yesterday':
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                startDateInput.value = formatDate(yesterday);
                endDateInput.value = formatDate(yesterday);
                break;
            case '7days':
                const last7Days = new Date(today);
                last7Days.setDate(last7Days.getDate() - 6);
                startDateInput.value = formatDate(last7Days);
                endDateInput.value = formatDate(today);
                break;
            case '30days':
                const last30Days = new Date(today);
                last30Days.setDate(last30Days.getDate() - 29);
                startDateInput.value = formatDate(last30Days);
                endDateInput.value = formatDate(today);
                break;
            case 'this_month':
                const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                startDateInput.value = formatDate(firstDayOfMonth);
                endDateInput.value = formatDate(today);
                break;
            case 'last_month':
                const firstDayOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastDayOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                startDateInput.value = formatDate(firstDayOfLastMonth);
                endDateInput.value = formatDate(lastDayOfLastMonth);
                break;
            case 'this_year':
                const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
                startDateInput.value = formatDate(firstDayOfYear);
                endDateInput.value = formatDate(today);
                break;
            case 'last_year':
                const firstDayOfLastYear = new Date(today.getFullYear() - 1, 0, 1);
                const lastDayOfLastYear = new Date(today.getFullYear() - 1, 11, 31);
                startDateInput.value = formatDate(firstDayOfLastYear);
                endDateInput.value = formatDate(lastDayOfLastYear);
                break;
        }
    }
    
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    // 売上グラフ
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesData);
    const isMonthly = {{ $isMonthly ? 'true' : 'false' }};
    
    const periods = salesData.map(data => {
        return isMonthly ? data.period : data.period.split('-').join('/');
    });
    const sales = salesData.map(data => data.total_sales);
    const orders = salesData.map(data => data.order_count);
    
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: periods,
            datasets: [
                {
                    label: '売上',
                    data: sales,
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    yAxisID: 'y',
                    tension: 0.1
                },
                {
                    label: '注文数',
                    data: orders,
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 2,
                    yAxisID: 'y1',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: '売上 (¥)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: '注文数'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // 支払い方法別グラフ
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentMethodStats = @json($paymentMethodStats);
    
    const paymentMethods = paymentMethodStats.map(data => data.payment_method);
    const paymentTotals = paymentMethodStats.map(data => data.total);
    const backgroundColor = [
        'rgba(79, 70, 229, 0.7)',
        'rgba(245, 158, 11, 0.7)',
        'rgba(16, 185, 129, 0.7)',
        'rgba(239, 68, 68, 0.7)',
        'rgba(59, 130, 246, 0.7)',
        'rgba(217, 70, 239, 0.7)'
    ];
    
    const paymentMethodChart = new Chart(paymentMethodCtx, {
        type: 'pie',
        data: {
            labels: paymentMethods,
            datasets: [{
                data: paymentTotals,
                backgroundColor: backgroundColor.slice(0, paymentMethods.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>
@endsection 