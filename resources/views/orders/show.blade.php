<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('注文詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- フラッシュメッセージ -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- 戻るボタン -->
                    <div class="mb-6">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            注文履歴一覧に戻る
                        </a>
                    </div>

                    <!-- 注文情報ヘッダー -->
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold">注文番号: {{ $order->order_number }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">注文日時: {{ $order->created_at->format('Y年m月d日 H:i') }}</p>
                        </div>
                        <div class="flex items-center">
                            <span class="inline-flex items-center">
                                <span class="mr-2">注文状況:</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($order->order_status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                    @elseif($order->order_status == 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                    @elseif($order->order_status == 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                    @endif">
                                    @if($order->order_status == 'pending') 処理待ち
                                    @elseif($order->order_status == 'processing') 処理中
                                    @elseif($order->order_status == 'completed') 完了
                                    @elseif($order->order_status == 'cancelled') キャンセル
                                    @else {{ $order->order_status }}
                                    @endif
                                </span>
                            </span>
                            
                            @if(($order->order_status == 'pending' || $order->order_status == 'processing'))
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="ml-4" onsubmit="return confirm('この注文をキャンセルしてもよろしいですか？');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        キャンセル
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- 注文詳細 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <!-- 注文商品 -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-3">注文商品</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">商品</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">単価</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">数量</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">小計</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div>
                                                                {{ $item->product_name }}
                                                                @if ($item->product)
                                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-blue-600 dark:text-blue-400 text-xs hover:underline ml-2">
                                                                        商品を見る
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ number_format($item->price) }}円
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ number_format($item->subtotal) }}円
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- 注文サマリー -->
                        <div>
                            <!-- 配送先住所 -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold mb-2">配送先情報</h4>
                                <p>{{ $order->shippingAddress->full_name }}</p>
                                <p>〒{{ $order->shippingAddress->postal_code }}</p>
                                <p>{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
                                @if ($order->shippingAddress->address_line2)
                                    <p>{{ $order->shippingAddress->address_line2 }}</p>
                                @endif
                                <p>電話番号: {{ $order->shippingAddress->phone }}</p>
                            </div>

                            <!-- 支払い情報 -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold mb-2">支払い情報</h4>
                                <p>支払い方法: クレジットカード（Stripe）</p>
                                <p>支払い状況: 
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->payment_status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @elseif($order->payment_status == 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($order->payment_status == 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @endif">
                                        @if($order->payment_status == 'pending') 未払い
                                        @elseif($order->payment_status == 'paid') 支払い済み
                                        @elseif($order->payment_status == 'failed') 失敗
                                        @else {{ $order->payment_status }}
                                        @endif
                                    </span>
                                </p>
                                @if($order->paid_at)
                                    <p>支払い日時: {{ $order->paid_at->format('Y年m月d日 H:i') }}</p>
                                @endif
                                
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        請求書
                                    </a>
                                    
                                    @if($order->payment_status == 'paid')
                                        <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            領収書
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- 金額情報 -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="font-semibold mb-2">金額情報</h4>
                                <div class="flex justify-between mb-1">
                                    <span>小計</span>
                                    <span>{{ number_format($order->subtotal) }}円</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span>消費税（10%）</span>
                                    <span>{{ number_format($order->tax) }}円</span>
                                </div>
                                <div class="flex justify-between mb-1">
                                    <span>送料</span>
                                    <span>{{ number_format($order->shipping_fee) }}円</span>
                                </div>
                                <div class="flex justify-between font-bold mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <span>合計</span>
                                    <span>{{ number_format($order->total) }}円</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 注文メモ -->
                    @if ($order->notes)
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold mb-2">注文メモ</h4>
                            <p class="text-gray-700 dark:text-gray-300">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 