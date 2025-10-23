@extends('layouts.admin')

@section('header', '銀行振込詳細')

@section('content')
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 振込情報 -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">振込情報</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">注文番号: {{ $bankTransfer->order->order_number }}</p>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">銀行名</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->bank_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">支店名</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->branch_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">口座種別</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->account_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">口座番号</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->account_number }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">口座名義</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->account_holder }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">振込金額</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($bankTransfer->transfer_amount) }}円</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">振込期限</dt>
                            <dd class="mt-1 text-sm {{ $bankTransfer->isExpired() ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-900 dark:text-white' }}">
                                {{ $bankTransfer->transfer_deadline->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ステータス</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($bankTransfer->transfer_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                    @elseif($bankTransfer->transfer_status === 'expired') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                    @if($bankTransfer->transfer_status === 'pending') 振込待ち
                                    @elseif($bankTransfer->transfer_status === 'confirmed') 確認済み
                                    @elseif($bankTransfer->transfer_status === 'expired') 期限切れ
                                    @else {{ $bankTransfer->transfer_status }}
                                    @endif
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">確認日時</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($bankTransfer->transfer_confirmed_at)
                                    {{ $bankTransfer->transfer_confirmed_at->format('Y年m月d日 H:i') }}
                                @else
                                    未確認
                                @endif
                            </dd>
                        </div>
                        @if($bankTransfer->confirmedBy)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">確認者</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->confirmedBy->name }}</dd>
                            </div>
                        @endif
                        @if($bankTransfer->admin_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">管理者メモ</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->admin_notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- 注文商品 -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">注文商品</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">商品</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">単価</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">数量</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">小計</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($bankTransfer->order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($item->product && $item->product->mainImage)
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded object-cover" src="{{ Storage::url($item->product->mainImage->image_path) }}" alt="{{ $item->product_name }}">
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ number_format($item->price) }}円
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ number_format($item->subtotal) }}円
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-right text-gray-900 dark:text-white" colspan="3">小計:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($bankTransfer->order->subtotal) }}円</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-right text-gray-900 dark:text-white" colspan="3">消費税:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($bankTransfer->order->tax) }}円</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-right text-gray-900 dark:text-white" colspan="3">送料:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ number_format($bankTransfer->order->shipping_fee) }}円</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-right text-gray-900 dark:text-white" colspan="3">合計:</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-white">{{ number_format($bankTransfer->order->total) }}円</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- サイドバー -->
        <div>
            <!-- 顧客情報 -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">顧客情報</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">氏名</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->order->user->name ?? '削除されたユーザー' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">メールアドレス</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->order->user->email ?? '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">注文日時</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->order->created_at->format('Y年m月d日 H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- 配送先情報 -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">配送先情報</h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">氏名</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->order->shippingAddress->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">住所</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                〒{{ $bankTransfer->order->shippingAddress->postal_code }}<br>
                                {{ $bankTransfer->order->shippingAddress->prefecture }}{{ $bankTransfer->order->shippingAddress->city }}{{ $bankTransfer->order->shippingAddress->address_line1 }}
                                @if($bankTransfer->order->shippingAddress->address_line2)
                                    <br>{{ $bankTransfer->order->shippingAddress->address_line2 }}
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">電話番号</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $bankTransfer->order->shippingAddress->phone }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- アクション -->
            @if($bankTransfer->transfer_status === 'pending')
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">アクション</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <!-- 振込確認フォーム -->
                        <form action="{{ route('admin.bank-transfers.confirm', $bankTransfer) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">管理者メモ（任意）</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" 
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="振込確認に関するメモを入力してください">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="return confirm('振込を確認済みにしますか？')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                振込確認
                            </button>
                        </form>

                        <!-- 期限切れ処理 -->
                        <form action="{{ route('admin.bank-transfers.mark-expired', $bankTransfer) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                onclick="return confirm('期限切れにしますか？この操作は元に戻せません。')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                期限切れ処理
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- ナビゲーション -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6 space-y-3">
                    <a href="{{ route('admin.bank-transfers.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        振込一覧に戻る
                    </a>
                    <a href="{{ route('admin.orders.show', $bankTransfer->order) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        注文詳細を見る
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection 