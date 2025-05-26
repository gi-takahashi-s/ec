<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('配送先住所の選択') }}
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
                            <div class="flex items-center text-blue-600 dark:text-blue-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-blue-600 dark:border-blue-400 text-center">
                                    <span class="text-xl font-bold">1</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-blue-600 dark:text-blue-400">住所</div>
                            </div>
                            <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300 dark:border-gray-600"></div>
                            <div class="flex items-center text-gray-500 dark:text-gray-400 relative">
                                <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 dark:border-gray-600 text-center">
                                    <span class="text-xl font-bold">2</span>
                                </div>
                                <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500 dark:text-gray-400">確認</div>
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

                    <h3 class="text-lg font-semibold mb-4">配送先住所を選択してください</h3>

                    @if ($addresses->count() > 0)
                        <form action="{{ route('checkout.select_address') }}" method="POST">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                @foreach ($addresses as $address)
                                    <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700' }}">
                                        <label class="flex items-start">
                                            <input type="radio" name="shipping_address_id" value="{{ $address->id }}" class="mt-1 mr-3" {{ $address->is_default ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-semibold">{{ $address->full_name }} 
                                                    @if ($address->is_default)
                                                        <span class="ml-2 px-2 py-1 text-xs bg-blue-500 text-white rounded-full">デフォルト</span>
                                                    @endif
                                                </div>
                                                <div class="text-gray-700 dark:text-gray-300 mt-1">
                                                    <p>〒{{ $address->postal_code }}</p>
                                                    <p>{{ $address->prefecture }}{{ $address->city }}{{ $address->address_line1 }}</p>
                                                    @if ($address->address_line2)
                                                        <p>{{ $address->address_line2 }}</p>
                                                    @endif
                                                    <p class="mt-1">電話番号: {{ $address->phone }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex items-center justify-between mt-8">
                                <a href="{{ route('shipping_addresses.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    新しい住所を追加
                                </a>
                                
                                <div class="flex items-center">
                                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                        カートに戻る
                                    </a>
                                    <x-primary-button>
                                        次へ進む
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">配送先住所が登録されていません。</p>
                            <a href="{{ route('shipping_addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                新しい住所を追加
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 