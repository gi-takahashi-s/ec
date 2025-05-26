<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ご注文ありがとうございます') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- チェックアウトステップ表示 -->
                    <div class="mb-8">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center text-green-600 dark:text-green-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-green-600 dark:border-green-400 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600 dark:text-green-400">住所</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-600 dark:border-green-400"></div>
                            <div class="flex items-center text-green-600 dark:text-green-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-green-600 dark:border-green-400 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600 dark:text-green-400">確認</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-600 dark:border-green-400"></div>
                            <div class="flex items-center text-green-600 dark:text-green-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-green-600 dark:border-green-400 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600 dark:text-green-400">完了</div>
                            </div>
                        </div>
                    </div>

                    <!-- 注文完了メッセージ -->
                    <div class="text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <svg class="w-16 h-16 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">ご注文ありがとうございます！</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-2">注文番号: {{ $order->order_number }}</p>
                        <p class="text-gray-600 dark:text-gray-300">注文内容の確認メールを送信しました。</p>
                    </div>

                    <!-- 注文詳細 -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 mb-8">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-600">
                            <h4 class="text-lg font-semibold">注文情報</h4>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h5 class="font-semibold mb-2">注文情報</h5>
                                    <p>注文日時: {{ $order->created_at->format('Y年m月d日 H:i') }}</p>
                                    <p>注文状況: 
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
                                    </p>
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
                                </div>
                                <div>
                                    <h5 class="font-semibold mb-2">配送先住所</h5>
                                    <p>{{ $order->shippingAddress->full_name }}</p>
                                    <p>〒{{ $order->shippingAddress->postal_code }}</p>
                                    <p>{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
                                    @if ($order->shippingAddress->address_line2)
                                        <p>{{ $order->shippingAddress->address_line2 }}</p>
                                    @endif
                                    <p>電話番号: {{ $order->shippingAddress->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 注文商品 -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 mb-8">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-600">
                            <h4 class="text-lg font-semibold">注文商品</h4>
                        </div>
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
                                <tfoot class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-right" colspan="3">小計:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($order->subtotal) }}円</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-right" colspan="3">消費税:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($order->tax) }}円</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-right" colspan="3">送料:</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($order->shipping_fee) }}円</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-right" colspan="3">合計:</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($order->total) }}円</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="flex justify-center">
                        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                            注文履歴を確認
                        </a>
                        <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            ショッピングを続ける
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 