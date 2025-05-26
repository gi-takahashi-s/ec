@extends('layouts.admin')

@section('header', 'ユーザー詳細')

@section('content')
    <!-- アクションボタン -->
    <div class="mb-6 flex justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                一覧に戻る
            </a>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                編集
            </a>
            @if(auth()->id() !== $user->id)
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                        onclick="return confirm('本当にこのユーザーを削除しますか？この操作は元に戻せません。')">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        削除
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- ユーザー基本情報 -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    ユーザー情報
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    ID: {{ $user->id }}
                </p>
            </div>
            <div class="flex items-center space-x-2">
                @if($user->is_admin)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                        管理者
                    </span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                        一般ユーザー
                    </span>
                @endif
                
                @if($user->email_verified_at)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                        メール確認済み
                    </span>
                @else
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                        メール未確認
                    </span>
                @endif
            </div>
        </div>
        
        <div class="border-b border-gray-200 dark:border-gray-700">
            <dl>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        名前
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $user->name }}
                    </dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        メールアドレス
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $user->email }}
                    </dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        メール確認日時
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $user->email_verified_at ? $user->email_verified_at->format('Y/m/d H:i') : '未確認' }}
                    </dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        登録日時
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $user->created_at->format('Y/m/d H:i') }}
                    </dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        最終更新日時
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $user->updated_at->format('Y/m/d H:i') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- 配送先住所リスト -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                配送先住所
            </h3>
        </div>
        
        @if($user->shippingAddresses && $user->shippingAddresses->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($user->shippingAddresses as $address)
                    <div class="px-4 py-5 sm:px-6">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">
                            {{ $address->last_name }} {{ $address->first_name }}
                            @if($address->is_default)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    デフォルト
                                </span>
                            @endif
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">〒{{ $address->postal_code }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $address->prefecture }}{{ $address->city }}{{ $address->address_line1 }}
                            @if($address->address_line2) {{ $address->address_line2 }} @endif
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">電話: {{ $address->phone }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-4 py-5 sm:px-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">配送先住所が登録されていません。</p>
            </div>
        @endif
    </div>

    <!-- 注文履歴 -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                注文履歴
            </h3>
        </div>
        
        @if($user->orders && $user->orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                注文番号
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                注文日
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                金額
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ステータス
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                アクション
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($user->orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ¥{{ number_format($order->total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->order_status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($order->order_status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                        @elseif($order->order_status === 'shipped') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                                        @elseif($order->order_status === 'delivered') bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100
                                        @elseif($order->order_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-4 py-5 sm:px-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">注文履歴がありません。</p>
            </div>
        @endif
    </div>
@endsection 