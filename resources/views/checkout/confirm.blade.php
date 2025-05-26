<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('注文内容の確認') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- フラッシュメッセージ -->
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

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
                            <div class="flex items-center text-blue-600 dark:text-blue-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-blue-600 dark:border-blue-400 text-center">
                                    <span class="text-xl font-bold">2</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600 dark:text-blue-400">確認</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300 dark:border-gray-600"></div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 dark:border-gray-600 text-center">
                                    <span class="text-xl font-bold">3</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">完了</div>
                            </div>
                        </div>
                    </div>

                    <!-- 注文内容確認 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <!-- 商品一覧 -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">注文商品</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    @foreach ($cart->items as $item)
                                        <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-600 last:border-0">
                                            <div class="w-16 h-16 flex-shrink-0 bg-gray-200 dark:bg-gray-600 rounded overflow-hidden">
                                                @if ($item->product->image_path)
                                                    <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h4 class="font-semibold">{{ $item->product->name }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ number_format($item->product->price) }}円 × {{ $item->quantity }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold">{{ number_format($item->product->price * $item->quantity) }}円</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 配送先住所 -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-lg font-semibold">配送先住所</h3>
                                    <a href="{{ route('checkout.address') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">変更</a>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="font-semibold">{{ $address->full_name }}</p>
                                    <p>〒{{ $address->postal_code }}</p>
                                    <p>{{ $address->prefecture }}{{ $address->city }}{{ $address->address_line1 }}</p>
                                    @if ($address->address_line2)
                                        <p>{{ $address->address_line2 }}</p>
                                    @endif
                                    <p class="mt-1">電話番号: {{ $address->phone }}</p>
                                </div>
                            </div>

                            <!-- 支払い方法 -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">支払い方法</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span>クレジットカード（Stripe決済）</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">注文確定後に決済画面に移動します。</p>
                                </div>
                            </div>
                        </div>

                        <!-- 注文サマリー -->
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 sticky top-6">
                                <h3 class="text-lg font-semibold mb-4">注文内容</h3>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between">
                                        <span>小計</span>
                                        <span>{{ number_format($subtotal) }}円</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>消費税（10%）</span>
                                        <span>{{ number_format($tax) }}円</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>送料</span>
                                        <span>{{ number_format($shippingFee) }}円</span>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mb-6">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>合計</span>
                                        <span>{{ number_format($total) }}円</span>
                                    </div>
                                </div>
                                
                                <form action="{{ route('checkout.process') }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">注文メモ（任意）</label>
                                        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <a href="{{ route('checkout.address') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                            戻る
                                        </a>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-gray-800 focus:bg-green-700 dark:focus:bg-gray-800 active:bg-green-800 dark:active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 w-full justify-center">
                                            注文を確定する
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 