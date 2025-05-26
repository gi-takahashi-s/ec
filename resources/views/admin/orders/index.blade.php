@extends('layouts.admin')

@section('header', '注文管理')

@section('content')
    <!-- 検索・フィルターパネル -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <!-- 検索フィールド -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">検索</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="注文番号、顧客名、メールなど">
                    </div>
                    
                    <!-- 注文ステータスフィルター -->
                    <div>
                        <label for="order_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">注文ステータス</label>
                        <select name="order_status" id="order_status" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">すべてのステータス</option>
                            @foreach($orderStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('order_status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- 支払いステータスフィルター -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">支払いステータス</label>
                        <select name="payment_status" id="payment_status" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">すべてのステータス</option>
                            @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- 日付範囲フィルター -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">開始日</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">終了日</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        検索・フィルター
                    </button>
                    
                    @if(request('search') || request('order_status') || request('payment_status') || request('start_date') || request('end_date'))
                        <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                            フィルターをクリア
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <!-- 注文リスト -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文番号
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        顧客
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文日
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        金額
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文ステータス
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        支払いステータス
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        アクション
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $order->user->name ?? '削除されたユーザー' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->user->email ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            ¥{{ number_format($order->total) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->order_status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($order->order_status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @elseif($order->order_status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                @elseif($order->order_status === 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                                @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                {{ $orderStatuses[$order->order_status] ?? $order->order_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                @elseif($order->payment_status === 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                {{ $paymentStatuses[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                                詳細
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                    onclick="return confirm('本当にこの注文を削除しますか？この操作は元に戻せません。')">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            注文が見つかりませんでした。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- ページネーション -->
    <div class="mt-4">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection 