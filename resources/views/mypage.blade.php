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
                            <a href="{{ route('shipping_addresses.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                配送先住所を管理する
                            </a>
                        </div>
                    </div>

                    <!-- 最近の注文 -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">最近の注文</h3>
                    
                    @if(Auth::user()->orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-6 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">注文番号</th>
                                        <th class="py-3 px-6 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">注文日</th>
                                        <th class="py-3 px-6 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">合計金額</th>
                                        <th class="py-3 px-6 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">状態</th>
                                        <th class="py-3 px-6 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">アクション</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach(Auth::user()->orders->take(3) as $order)
                                        <tr>
                                            <td class="py-4 px-6 text-sm">{{ $order->order_number }}</td>
                                            <td class="py-4 px-6 text-sm">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                            <td class="py-4 px-6 text-sm">{{ number_format($order->total) }}円</td>
                                            <td class="py-4 px-6 text-sm">
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
                                            </td>
                                            <td class="py-4 px-6 text-sm">
                                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 dark:text-blue-400 hover:underline">詳細</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">すべての注文履歴を見る →</a>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>注文履歴はまだありません。</p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-blue-600 dark:text-blue-400 hover:underline">商品一覧を見る</a>
                        </div>
                    @endif

                    <!-- 会員情報設定 -->
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 my-6">会員情報設定</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- プロフィール編集 -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <div class="flex items-center mb-3">
                                <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">プロフィール編集</h4>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">お名前やメールアドレスなどの会員情報を変更できます。</p>
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center">
                                編集する
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- 配送先住所 -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <div class="flex items-center mb-3">
                                <svg class="w-6 h-6 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">配送先住所</h4>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">複数の配送先住所を登録・管理できます。</p>
                            <a href="{{ route('shipping_addresses.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center">
                                住所を管理する
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- 注文履歴 -->
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <div class="flex items-center mb-3">
                                <svg class="w-6 h-6 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">注文履歴</h4>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">過去の注文履歴を確認できます。</p>
                            <a href="{{ route('orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center">
                                履歴を見る
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
