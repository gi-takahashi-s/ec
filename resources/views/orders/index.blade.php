<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('注文履歴') }}
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

                    <!-- 注文履歴 -->
                    @if ($orders->count() > 0)
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
                                    @foreach ($orders as $order)
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
                                                
                                                @if($order->order_status == 'pending' || $order->order_status == 'processing')
                                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline ml-3" onsubmit="return confirm('この注文をキャンセルしてもよろしいですか？');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">キャンセル</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>注文履歴がありません。</p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-blue-600 dark:text-blue-400 hover:underline">商品一覧を見る</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 