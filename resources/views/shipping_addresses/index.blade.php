<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('配送先住所一覧') }}
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

                    <!-- アクションボタン -->
                    <div class="mb-6">
                        <a href="{{ route('shipping_addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            新規住所を追加
                        </a>
                    </div>

                    <!-- 住所一覧 -->
                    @if ($addresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($addresses as $address)
                                <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold flex items-center">
                                            {{ $address->full_name }}
                                            @if ($address->is_default)
                                                <span class="ml-2 px-2 py-1 text-xs bg-green-500 text-white rounded-full">デフォルト</span>
                                            @endif
                                        </h3>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('shipping_addresses.edit', $address) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                編集
                                            </a>
                                            @if (!$address->is_default)
                                                <form action="{{ route('shipping_addresses.set_default', $address) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:underline">
                                                        デフォルトに設定
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('shipping_addresses.destroy', $address) }}" method="POST" class="inline" onsubmit="return confirm('この住所を削除してもよろしいですか？');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <p>〒{{ $address->postal_code }}</p>
                                        <p>{{ $address->prefecture }}{{ $address->city }}{{ $address->address_line1 }}</p>
                                        @if ($address->address_line2)
                                            <p>{{ $address->address_line2 }}</p>
                                        @endif
                                        <p class="mt-2">電話番号: {{ $address->phone }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>配送先住所が登録されていません。</p>
                            <p class="mt-2">「新規住所を追加」ボタンから配送先を登録してください。</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 