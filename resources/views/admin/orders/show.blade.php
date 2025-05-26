@extends('layouts.admin')

@section('header', '注文詳細')

@section('content')
    <!-- アクションボタン -->
    <div class="mb-6 flex justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                一覧に戻る
            </a>
        </div>
        <div>
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                    onclick="return confirm('本当にこの注文を削除しますか？この操作は元に戻せません。')">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    削除
                </button>
            </form>
        </div>
    </div>

    <!-- 注文基本情報 -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    注文情報
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    注文番号: {{ $order->order_number }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($order->order_status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                    @elseif($order->order_status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                    @elseif($order->order_status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                    @elseif($order->order_status === 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                    @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                    {{ $orderStatuses[$order->order_status] ?? $order->order_status }}
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
            <!-- 顧客情報 -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">顧客情報</h4>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <p class="text-sm text-gray-900 dark:text-white">{{ $order->user->name ?? '削除されたユーザー' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email ?? '' }}</p>
                </div>
            </div>

            <!-- 配送先情報 -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">配送先</h4>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    @if($order->shippingAddress)
                        <p class="text-sm text-gray-900 dark:text-white">{{ $order->shippingAddress->last_name }} {{ $order->shippingAddress->first_name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">〒{{ $order->shippingAddress->postal_code }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
                        @if($order->shippingAddress->address_line2)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->shippingAddress->address_line2 }}</p>
                        @endif
                        <p class="text-sm text-gray-500 dark:text-gray-400">電話: {{ $order->shippingAddress->phone }}</p>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">配送先情報がありません</p>
                    @endif
                </div>
            </div>

            <!-- 注文情報 -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">支払い情報</h4>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                    <p class="text-sm text-gray-900 dark:text-white">支払い方法: {{ $order->payment_method }}</p>
                    <p class="text-sm text-gray-900 dark:text-white">
                        支払い状況: 
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                            @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                            {{ $order->payment_status }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">注文日: {{ $order->created_at->format('Y/m/d H:i') }}</p>
                    @if($order->paid_at)
                        <p class="text-sm text-gray-500 dark:text-gray-400">支払日: {{ $order->paid_at->format('Y/m/d H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 注文ステータス更新 -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                注文ステータス更新
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="order_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">注文ステータス</label>
                        <select id="order_status" name="order_status" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($orderStatuses as $value => $label)
                                <option value="{{ $value }}" {{ $order->order_status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">備考</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $order->notes }}</textarea>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        ステータスを更新
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 注文詳細 -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                注文内容
            </h3>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            商品
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            単価
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            数量
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            小計
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($item->product && $item->product->mainImage)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($item->product->mainImage->path) }}" alt="{{ $item->product_name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->product_name }}
                                        </div>
                                        @if($item->product)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <a href="{{ route('admin.products.show', $item->product) }}" class="hover:underline">
                                                    商品詳細を見る
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ¥{{ number_format($item->price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                                ¥{{ number_format($item->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                            小計
                        </td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                            ¥{{ number_format($order->subtotal) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                            消費税
                        </td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                            ¥{{ number_format($order->tax) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                            配送料
                        </td>
                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                            ¥{{ number_format($order->shipping_fee) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                            合計
                        </td>
                        <td class="px-6 py-3 text-right text-lg font-bold text-gray-900 dark:text-white">
                            ¥{{ number_format($order->total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection