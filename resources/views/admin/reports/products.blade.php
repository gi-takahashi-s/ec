@extends('layouts.admin')

@section('header', '商品レポート')

@section('content')
    <!-- 期間選択 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.reports.products') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4">
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
                    
                    <!-- 期間ショートカット -->
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">期間</label>
                        <select id="period" onchange="setPeriod(this.value)" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">選択してください</option>
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
    
    <!-- 在庫状況サマリー -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                在庫状況
            </h3>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <!-- 総商品数 -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                総商品数
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($stockStatus['total']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 在庫あり -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                在庫あり
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-500">
                                {{ number_format($stockStatus['in_stock']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $stockStatus['total'] > 0 ? number_format(($stockStatus['in_stock'] / $stockStatus['total']) * 100, 1) : 0 }}% の商品
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 在庫切れ -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                在庫切れ
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-500">
                                {{ number_format($stockStatus['out_of_stock']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $stockStatus['total'] > 0 ? number_format(($stockStatus['out_of_stock'] / $stockStatus['total']) * 100, 1) : 0 }}% の商品
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <!-- 在庫残りわずか -->
                <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                残りわずか
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold text-yellow-600 dark:text-yellow-500">
                                {{ number_format($stockStatus['low_stock']) }}
                            </dd>
                            <dd class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $stockStatus['total'] > 0 ? number_format(($stockStatus['low_stock'] / $stockStatus['total']) * 100, 1) : 0 }}% の商品
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 人気商品トップ10 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                人気商品トップ10（{{ $startDate->format('Y/m/d') }} 〜 {{ $endDate->format('Y/m/d') }}）
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                商品名
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                SKU
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                販売数
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                売上
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topProducts as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $product->sku }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    {{ number_format($product->total_quantity) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                    ¥{{ number_format($product->total_sales) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    この期間の注文データがありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- カテゴリ別売上 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                カテゴリ別売上
            </h3>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- グラフ -->
                <div class="h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
                
                <!-- テーブル -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    カテゴリ
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    注文数
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    販売数
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    売上
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($categoryStats as $category)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ number_format($category->order_count) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ number_format($category->total_quantity) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                        ¥{{ number_format($category->total_sales) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        この期間のカテゴリ別データがありません。
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
    
    // カテゴリ別売上グラフ
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryStats = @json($categoryStats);
    
    // データの準備
    const categories = categoryStats.map(stat => stat.name);
    const categorySales = categoryStats.map(stat => stat.total_sales);
    const categoryQuantities = categoryStats.map(stat => stat.total_quantity);
    
    // グラフの設定
    const categoryChart = new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [
                {
                    label: '売上 (¥)',
                    data: categorySales,
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: '販売数',
                    data: categoryQuantities,
                    backgroundColor: 'rgba(245, 158, 11, 0.7)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1'
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
                        text: '販売数'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endsection 