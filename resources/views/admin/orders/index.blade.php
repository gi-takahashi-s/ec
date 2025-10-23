@extends('layouts.admin')

@section('header', '注文管理')

@section('content')
<!-- 銀行振込統計情報 -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">振込待ち</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $bankTransferStats['pending'] }}件</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">確認済み</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $bankTransferStats['confirmed'] }}件</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">期限切れ</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $bankTransferStats['expired'] }}件</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">振込待ち金額</dt>
                        <dd class="text-lg font-medium text-gray-900 dark:text-white">¥{{ number_format($bankTransferStats['total_amount_pending']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 検索・フィルターパネル -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-4">
            <!-- 検索フィールド（横幅いっぱい） -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">検索</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="注文番号、顧客名、メールなど">
            </div>

            <!-- その他のフィルター項目 -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
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

                <!-- 支払い方法フィルター -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">支払い方法</label>
                    <select name="payment_method" id="payment_method"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">すべての方法</option>
                        @foreach($paymentMethods as $value => $label)
                        <option value="{{ $value }}" {{ request('payment_method') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- 銀行振込ステータスフィルター -->
                <div>
                    <label for="bank_transfer_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">振込ステータス</label>
                    <select name="bank_transfer_status" id="bank_transfer_status"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">すべてのステータス</option>
                        @foreach($bankTransferStatuses as $value => $label)
                        <option value="{{ $value }}" {{ request('bank_transfer_status') == $value ? 'selected' : '' }}>
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

                @if(request('search') || request('order_status') || request('payment_status') || request('payment_method') || request('bank_transfer_status') || request('start_date') || request('end_date'))
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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文者
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文日付
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        支払い方法
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文ステータス
                    </th>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        購入金額
                    </th>
                    <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        アクション
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($orders as $order)
                <tr>
                    <td class="px-3 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $order->order_number }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->user->name ?? '削除されたユーザー' }}
                        </div>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $order->created_at->format('Y/m/d') }}
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $order->created_at->format('H:i') }}
                        </div>
                    </td>
                    <td class="px-3 py-4">
                        <div class="flex flex-col space-y-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full w-fit bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}
                            </span>
                            @if($order->payment_method === 'bank_transfer' && $order->bankTransfer)
                            <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full w-fit
                                            @if($order->bankTransfer->transfer_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($order->bankTransfer->transfer_status === 'expired') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                {{ $bankTransferStatuses[$order->bankTransfer->transfer_status] ?? $order->bankTransfer->transfer_status }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-4">
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
                    <td class="px-3 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            ¥{{ number_format($order->total) }}
                        </div>
                        <div class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full w-fit
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                        @elseif($order->payment_status === 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                {{ $paymentStatuses[$order->payment_status] ?? $order->payment_status }}
                            </span>
                        </div>
                    </td>
                    <td class="px-3 py-4 text-right text-sm font-medium">
                        <div class="flex flex-row space-x-2 justify-end">
                            @if($order->bankTransfer && $order->bankTransfer->transfer_status === 'pending')
                            <form action="{{ route('admin.orders.confirm-bank-transfer', $order) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="redirect_to" value="index">
                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    onclick="return confirm('この銀行振込を確認済みにしますか？')">
                                    振込確認
                                </button>
                            </form>
                            @endif
                            @if($order->order_status === 'processing')
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="shipped">
                                <input type="hidden" name="redirect_to" value="index">
                                <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                    onclick="return confirm('この注文を発送済みにしますか？')">
                                    発送済み
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                詳細
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                        注文が見つかりませんでした。
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ページネーション -->
<div class="mt-4">
    {{ $orders->withQueryString()->links() }}
</div>
@endsection