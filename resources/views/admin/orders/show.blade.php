@extends('layouts.admin')

@section('header', '注文詳細')

@section('content')
<!-- アクションボタン -->
<div class="mb-6 flex justify-between">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            一覧に戻る
        </a>
    </div>
    <div class="flex space-x-2">
        @if($order->bankTransfer && $order->bankTransfer->transfer_status === 'pending')
        <form action="{{ route('admin.orders.confirm-bank-transfer', $order) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                onclick="return confirm('この銀行振込を確認済みにしますか？')">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                振込確認
            </button>
        </form>
        <form action="{{ route('admin.orders.mark-bank-transfer-expired', $order) }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                onclick="return confirm('この銀行振込を期限切れにしますか？注文もキャンセルされます。')">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                期限切れ
            </button>
        </form>
        @endif
        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                onclick="return confirm('本当にこの注文を削除しますか？この操作は元に戻せません。')">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                削除
            </button>
        </form>
    </div>
</div>

<!-- 注文基本情報 -->
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                注文情報
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                注文番号: {{ $order->order_number }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    @if($order->order_status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                    @elseif($order->order_status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                    @elseif($order->order_status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                    @elseif($order->order_status === 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                    @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                {{ $orderStatuses[$order->order_status] ?? $order->order_status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6">
        <!-- 顧客情報 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">顧客情報</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                <p class="text-sm text-gray-900 dark:text-white">{{ $order->user->name ?? '削除されたユーザー' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->user->email ?? '' }}</p>
            </div>
        </div>

        <!-- 配送先情報 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">配送先</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                @if($order->shippingAddress)
                <p class="text-sm text-gray-900 dark:text-white">{{ $order->shippingAddress->last_name }} {{ $order->shippingAddress->first_name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">〒{{ $order->shippingAddress->postal_code }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
                @if($order->shippingAddress->address_line2)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->shippingAddress->address_line2 }}</p>
                @endif
                <p class="text-sm text-gray-500 dark:text-gray-400">電話: {{ $order->shippingAddress->phone }}</p>
                @else
                <p class="text-sm text-gray-500 dark:text-gray-400">配送先情報がありません</p>
                @endif
            </div>
        </div>

        <!-- 支払い情報 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">支払い情報</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                <p class="text-sm text-gray-900 dark:text-white">
                    支払い方法:
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                        @if($order->payment_method === 'credit_card' || $order->payment_method === 'stripe') クレジットカード
                        @elseif($order->payment_method === 'bank_transfer') 銀行振込
                        @elseif($order->payment_method === 'cash_on_delivery') 代金引換
                        @else {{ $order->payment_method }} @endif
                    </span>
                </p>
                <p class="text-sm text-gray-900 dark:text-white">
                    支払い状況:
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($order->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                            @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                            @elseif($order->payment_status === 'failed') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                        @if($order->payment_status === 'paid') 決済済み
                        @elseif($order->payment_status === 'pending') 未決済
                        @elseif($order->payment_status === 'failed') 決済失敗
                        @elseif($order->payment_status === 'refunded') 返金済み
                        @else {{ $order->payment_status }} @endif
                    </span>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">注文日: {{ $order->created_at->format('Y/m/d H:i') }}</p>
                @if($order->paid_at)
                <p class="text-sm text-gray-500 dark:text-gray-400">支払日: {{ $order->paid_at->format('Y/m/d H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- 配送情報 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">配送情報</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                @if($order->tracking_number)
                <p class="text-sm text-gray-900 dark:text-white">お問い合わせ番号: {{ $order->tracking_number }}</p>
                @endif
                @if($order->shipping_method)
                <p class="text-sm text-gray-900 dark:text-white">配送方法: {{ $order->shipping_method }}</p>
                @endif
                @if($order->delivery_date)
                <p class="text-sm text-gray-900 dark:text-white">お届け日: {{ $order->delivery_date->format('Y/m/d') }}</p>
                @endif
                @if($order->delivery_time)
                <p class="text-sm text-gray-900 dark:text-white">お届け時間: {{ $order->delivery_time }}</p>
                @endif
                @if($order->shipped_at)
                <p class="text-sm text-gray-500 dark:text-gray-400">発送日: {{ $order->shipped_at->format('Y/m/d H:i') }}</p>
                @endif
                @if($order->delivered_at)
                <p class="text-sm text-gray-500 dark:text-gray-400">配達日: {{ $order->delivered_at->format('Y/m/d H:i') }}</p>
                @endif
                @if($order->shipping_memo)
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">出荷用メモ:</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $order->shipping_memo }}</p>
                </div>
                @endif
                @if(!$order->tracking_number && !$order->shipping_method && !$order->delivery_date && !$order->delivery_time && !$order->shipping_memo)
                <p class="text-sm text-gray-500 dark:text-gray-400">配送情報が未設定です</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 銀行振込情報 -->
@if($order->bankTransfer)
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                銀行振込情報
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                振込に関する詳細情報
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($order->bankTransfer->transfer_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                        @elseif($order->bankTransfer->transfer_status === 'expired') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                @if($order->bankTransfer->transfer_status === 'pending') 振込待ち
                @elseif($order->bankTransfer->transfer_status === 'confirmed') 確認済み
                @elseif($order->bankTransfer->transfer_status === 'expired') 期限切れ
                @else {{ $order->bankTransfer->transfer_status }} @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        <!-- 振込先情報 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">振込先情報</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                <p class="text-sm text-gray-900 dark:text-white">銀行名: {{ $order->bankTransfer->bank_name }}</p>
                <p class="text-sm text-gray-900 dark:text-white">支店名: {{ $order->bankTransfer->branch_name }}</p>
                <p class="text-sm text-gray-900 dark:text-white">口座種別: {{ $order->bankTransfer->account_type }}</p>
                <p class="text-sm text-gray-900 dark:text-white">口座番号: {{ $order->bankTransfer->account_number }}</p>
                <p class="text-sm text-gray-900 dark:text-white">口座名義: {{ $order->bankTransfer->account_holder }}</p>
            </div>
        </div>

        <!-- 振込詳細 -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">振込詳細</h4>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                <p class="text-sm text-gray-900 dark:text-white">振込金額: ¥{{ number_format($order->bankTransfer->transfer_amount) }}</p>
                <p class="text-sm text-gray-900 dark:text-white">
                    振込期限: {{ $order->bankTransfer->transfer_deadline->format('Y/m/d H:i') }}
                    @if($order->bankTransfer->transfer_status === 'pending' && $order->bankTransfer->isExpired())
                    <span class="text-red-600 dark:text-red-400 text-xs ml-2">(期限切れ)</span>
                    @endif
                </p>
                @if($order->bankTransfer->transfer_confirmed_at)
                <p class="text-sm text-gray-900 dark:text-white">確認日時: {{ $order->bankTransfer->transfer_confirmed_at->format('Y/m/d H:i') }}</p>
                @endif
                @if($order->bankTransfer->confirmedBy)
                <p class="text-sm text-gray-500 dark:text-gray-400">確認者: {{ $order->bankTransfer->confirmedBy->name }}</p>
                @endif
                @if($order->bankTransfer->admin_notes)
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">管理者メモ:</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $order->bankTransfer->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 銀行振込管理アクション -->
    @if($order->bankTransfer->transfer_status === 'pending')
    <div class="px-6 pb-6">
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        振込確認待ち
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p>この注文は銀行振込による支払いを待っています。振込を確認したら「振込確認」ボタンをクリックしてください。</p>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('admin.orders.confirm-bank-transfer', $order) }}" method="POST" class="inline-block mr-4">
                            @csrf
                            <div class="mb-3">
                                <label for="admin_notes" class="block text-sm font-medium text-yellow-800 dark:text-yellow-200">管理者メモ（任意）</label>
                                <textarea id="admin_notes" name="admin_notes" rows="2"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="振込確認に関するメモがあれば入力してください"></textarea>
                            </div>
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                onclick="return confirm('この銀行振込を確認済みにしますか？')">
                                振込確認
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- 注文ステータス更新 -->
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            注文ステータス・配送情報更新
        </h3>
    </div>
    <div class="p-6">
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="order_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">注文ステータス</label>
                    <select id="order_status" name="order_status"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($orderStatuses as $value => $label)
                        <option value="{{ $value }}" {{ $order->order_status === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">備考</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $order->notes }}</textarea>
                </div>
            </div>

            <!-- 配送情報セクション -->
            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">配送情報</h4>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">お問い合わせ番号</label>
                        <input type="text" id="tracking_number" name="tracking_number" value="{{ $order->tracking_number }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="配送業者のお問い合わせ番号">
                    </div>
                    <div>
                        <label for="shipping_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">配送方法</label>
                        <input type="text" id="shipping_method" name="shipping_method" value="{{ $order->shipping_method }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="配送方法">
                    </div>
                    <div>
                        <label for="delivery_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">お届け日</label>
                        <input type="date" id="delivery_date" name="delivery_date" value="{{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '' }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="delivery_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">お届け時間</label>
                        <input type="text" id="delivery_time" name="delivery_time" value="{{ $order->delivery_time }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="例: 午前中、14:00-16:00">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="shipping_memo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">出荷用メモ欄</label>
                        <textarea id="shipping_memo" name="shipping_memo" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="出荷に関するメモや特記事項">{{ $order->shipping_memo }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ステータス・配送情報を更新
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 注文詳細 -->
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            注文内容
        </h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    商品
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    単価
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    数量
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    小計
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($order->items as $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if($item->product && $item->product->mainImage && $item->product->mainImage->image_path && Storage::exists($item->product->mainImage->image_path))
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($item->product->mainImage->image_path) }}" alt="{{ $item->product_name }}">
                            @else
                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->product_name }}
                            </div>
                            @if($item->product)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <a href="{{ route('admin.products.show', $item->product) }}" class="hover:underline">
                                    商品詳細を見る
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ¥{{ number_format($item->price) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-white">
                    ¥{{ number_format($item->subtotal) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                    小計
                </td>
                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                    ¥{{ number_format($order->subtotal) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                    消費税
                </td>
                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                    ¥{{ number_format($order->tax) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300">
                    配送料
                </td>
                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                    ¥{{ number_format($order->shipping_fee) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                    合計
                </td>
                <td class="px-6 py-3 text-right text-lg font-bold text-gray-900 dark:text-white">
                    ¥{{ number_format($order->total) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection