<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('配送業者・配送時間の選択') }}
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
                            <div class="flex items-center text-blue-600 dark:text-blue-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-blue-600 dark:border-blue-400 text-center">
                                    <span class="text-xl font-bold">2</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600 dark:text-blue-400">配送</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300 dark:border-gray-600"></div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 dark:border-gray-600 text-center">
                                    <span class="text-xl font-bold">3</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">支払い</div>
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

                    <form action="{{ route('checkout.select_shipping') }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">配送業者を選択してください</h3>
                            
                            @if($shippingCompanies->count() > 0)
                                <div class="space-y-4">
                                    @foreach($shippingCompanies as $company)
                                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                                            <label class="flex items-start cursor-pointer">
                                                <input type="radio" 
                                                       name="shipping_company_id" 
                                                       value="{{ $company->id }}" 
                                                       class="mt-1 mr-3" 
                                                       {{ $shippingCompanies->count() === 1 ? 'checked' : '' }}
                                                       {{ old('shipping_company_id') == $company->id ? 'checked' : '' }}
                                                       onchange="updateDeliveryTimes({{ $company->id }}, {{ json_encode($company->delivery_times) }})">
                                                <div class="flex-1">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="w-6 h-6 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                        <span class="font-semibold">{{ $company->name }}</span>
                                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $company->method_name }}</span>
                                                    </div>
                                                    
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                        @if($company->uniform_shipping_fee)
                                                            <span class="font-medium">送料: {{ number_format($company->uniform_fee) }}円（全国一律）</span>
                                                        @else
                                                            <span class="font-medium">送料: 地域別料金</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($company->notes)
                                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                                            {{ $company->notes }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                @error('shipping_company_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500 dark:text-gray-400">利用可能な配送業者がありません。</p>
                                </div>
                            @endif
                        </div>

                        <!-- 配送時間選択 -->
                        <div class="mb-8" id="delivery-time-section" style="display: none;">
                            <h3 class="text-lg font-semibold mb-4">配送時間を選択してください（任意）</h3>
                            
                            <div class="space-y-2" id="delivery-time-options">
                                <label class="flex items-center">
                                    <input type="radio" name="delivery_time" value="" class="mr-2" checked>
                                    <span class="text-sm">指定なし</span>
                                </label>
                            </div>

                            @error('delivery_time')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ボタン -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('checkout.address') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
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

    <script>
        function updateDeliveryTimes(companyId, deliveryTimes) {
            const section = document.getElementById('delivery-time-section');
            const options = document.getElementById('delivery-time-options');
            
            if (deliveryTimes && deliveryTimes.length > 0) {
                // 既存のオプションをクリア
                options.innerHTML = '';
                
                // 「指定なし」オプションを追加
                const noSpecifyOption = document.createElement('label');
                noSpecifyOption.className = 'flex items-center';
                noSpecifyOption.innerHTML = `
                    <input type="radio" name="delivery_time" value="" class="mr-2" checked>
                    <span class="text-sm">指定なし</span>
                `;
                options.appendChild(noSpecifyOption);
                
                // 配送時間オプションを追加
                deliveryTimes.forEach(function(time) {
                    const option = document.createElement('label');
                    option.className = 'flex items-center';
                    option.innerHTML = `
                        <input type="radio" name="delivery_time" value="${time}" class="mr-2">
                        <span class="text-sm">${time}</span>
                    `;
                    options.appendChild(option);
                });
                
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        // ページ読み込み時に配送業者が1つしかない場合は自動選択
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="shipping_company_id"]:checked');
            if (checkedRadio) {
                const companyId = checkedRadio.value;
                const onchangeAttr = checkedRadio.getAttribute('onchange');
                if (onchangeAttr) {
                    const match = onchangeAttr.match(/\[(.*?)\]/);
                    if (match) {
                        try {
                            const deliveryTimes = JSON.parse('[' + match[1] + ']');
                            updateDeliveryTimes(companyId, deliveryTimes);
                        } catch (e) {
                            console.error('Failed to parse delivery times:', e);
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout> 