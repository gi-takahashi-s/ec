<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('マイページ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- ユーザー情報 -->
                    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ Auth::user()->name }}様、ようこそ</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            最終ログイン日時: {{ Auth::user()->updated_at->format('Y年m月d日 H:i') }}
                        </p>
                        
                        <div class="flex flex-wrap gap-3 mt-4">
                            <a href="{{ route('orders.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                注文履歴を見る
                            </a>
                            <a href="{{ route('profile.edit') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                会員情報を編集する
                            </a>
                        </div>
                    </div>

                    <!-- 最近の注文 -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">最近の注文</h3>
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>注文履歴はまだありません。</p>
                        <a href="{{ route('products.index') }}" class="inline-block mt-4 text-blue-600 dark:text-blue-400 hover:underline">商品一覧を見る</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.location.href = "{{ route('mypage') }}";
    </script>
</x-app-layout>
