<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ご注文ありがとうございます') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
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

                    <!-- 銀行振込の場合の振込先情報 -->
                    @if($order->isBankTransfer() && $order->bankTransfer)
                        @php
                            // 管理画面で設定した最新の銀行振込情報を取得
                            $paymentMethod = \App\Models\PaymentMethod::where('key', \App\Models\PaymentMethod::BANK_TRANSFER)->first();
                            $settings = $paymentMethod ? $paymentMethod->settings : [];
                            
                            // 管理画面の設定がある場合は優先、なければbankTransferの情報を使用
                            $bankInfo = [
                                'bank_name' => $settings['bank_name'] ?? $order->bankTransfer->bank_name,
                                'branch_name' => $settings['bank_branch'] ?? $order->bankTransfer->branch_name,
                                'account_type' => $settings['account_type'] ?? $order->bankTransfer->account_type,
                                'account_number' => $settings['account_number'] ?? $order->bankTransfer->account_number,
                                'account_holder' => $settings['account_name'] ?? $order->bankTransfer->account_holder,
                                'transfer_amount' => $order->bankTransfer->transfer_amount,
                                'transfer_deadline' => $order->bankTransfer->transfer_deadline,
                            ];
                        @endphp
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">お振込先情報</h4>
                                    <div class="bg-white dark:bg-gray-800 rounded-md p-4 border border-blue-200 dark:border-blue-700">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">銀行名</p>
                                                <p class="font-semibold">{{ $bankInfo['bank_name'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">支店名</p>
                                                <p class="font-semibold">{{ $bankInfo['branch_name'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">口座種別</p>
                                                <p class="font-semibold">{{ $bankInfo['account_type'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">口座番号</p>
                                                <p class="font-semibold">{{ $bankInfo['account_number'] }}</p>
                                            </div>
                                            <div class="md:col-span-2">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">口座名義</p>
                                                <p class="font-semibold">{{ $bankInfo['account_holder'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">振込金額</p>
                                                <p class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ number_format($bankInfo['transfer_amount']) }}円</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">振込期限</p>
                                                <p class="font-semibold text-orange-600 dark:text-orange-400">{{ $bankInfo['transfer_deadline']->format('Y年m月d日') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 p-3 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div class="text-sm text-orange-800 dark:text-orange-200">
                                                <p class="font-medium mb-1">重要なお知らせ</p>
                                                @if(!empty($settings['notes']))
                                                    <div class="text-xs whitespace-pre-line">{{ $settings['notes'] }}</div>
                                                @else
                                                    <ul class="space-y-1 text-xs">
                                                        <li>• 振込期限内にお振込みをお願いいたします</li>
                                                        <li>• 振込手数料はお客様負担となります</li>
                                                        <li>• 振込確認後、商品を発送いたします</li>
                                                        <li>• 振込名義は注文者名と同一にしてください</li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 配送先住所 -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600 mb-8">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-600">
                            <h4 class="text-lg font-semibold">配送先住所</h4>
                        </div>
                        <div class="p-4">
                            <div class="space-y-2">
                                <p class="font-semibold">{{ $order->shippingAddress->full_name }}</p>
                                <p>〒{{ $order->shippingAddress->postal_code }}</p>
                                <p>{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
                                @if ($order->shippingAddress->address_line2)
                                    <p>{{ $order->shippingAddress->address_line2 }}</p>
                                @endif
                                <p>電話番号: {{ $order->shippingAddress->phone }}</p>
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