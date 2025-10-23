<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('支払い方法の選択') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                            <div class="flex items-center text-green-600 dark:text-green-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-green-600 dark:border-green-400 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600 dark:text-green-400">配送</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-green-600 dark:border-green-400"></div>
                            <div class="flex items-center text-blue-600 dark:text-blue-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-blue-600 dark:border-blue-400 text-center">
                                    <span class="text-xl font-bold">3</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600 dark:text-blue-400">支払い</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300 dark:border-gray-600"></div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 dark:border-gray-600 text-center">
                                    <span class="text-xl font-bold">4</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">確認</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300 dark:border-gray-600"></div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 dark:border-gray-600 text-center">
                                    <span class="text-xl font-bold">5</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">完了</div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('checkout.payment-method.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">支払い方法を選択してください</h3>
                            
                            <div class="space-y-4">
                                @foreach($paymentMethods as $method)
                                    @if($method->key === 'stripe')
                                        <!-- クレジットカード決済 -->
                                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                                            <label class="flex items-start cursor-pointer">
                                                <input type="radio" name="payment_method" value="stripe" class="mt-1 mr-3" {{ old('payment_method', 'stripe') === 'stripe' ? 'checked' : '' }}>
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                        </svg>
                                                        <span class="font-semibold">{{ $method->name }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $method->description }}
                                                    </p>
                                                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                        SSL暗号化により安全に決済されます
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @elseif($method->key === 'bank_transfer')
                                        <!-- 銀行振込 -->
                                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                                            <label class="flex items-start cursor-pointer">
                                                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 mr-3" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="w-6 h-6 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        <span class="font-semibold">{{ $method->name }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $method->description }}
                                                    </p>
                                                    @if(!empty($method->settings['notes']))
                                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        {!! nl2br(e($method->settings['notes'])) !!}
                                                    </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @elseif($method->key === 'cash_on_delivery')
                                        <!-- 代金引換 -->
                                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                                            <label class="flex items-start cursor-pointer">
                                                <input type="radio" name="payment_method" value="cash_on_delivery" class="mt-1 mr-3" {{ old('payment_method') === 'cash_on_delivery' ? 'checked' : '' }}>
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="w-6 h-6 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        <span class="font-semibold">{{ $method->name }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $method->description }}
                                                    </p>
                                                    @if(!empty($method->settings['notes']))
                                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                        {!! nl2br(e($method->settings['notes'])) !!}
                                                    </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 注文サマリー -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <h4 class="font-semibold mb-3">注文内容</h4>
                            <div class="space-y-2 text-sm">
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
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2 font-bold">
                                    <div class="flex justify-between">
                                        <span>合計</span>
                                        <span>{{ number_format($total) }}円</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ボタン -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('checkout.shipping') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                戻る
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:bg-blue-700 dark:focus:bg-blue-600 active:bg-blue-800 dark:active:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                次へ進む
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 