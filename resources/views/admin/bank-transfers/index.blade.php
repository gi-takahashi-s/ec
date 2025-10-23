@extends('layouts.admin')

@section('header', '銀行振込管理')

@section('content')
    <!-- 統計情報 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">振込待ち</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['pending'] }}件</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">確認済み</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['confirmed'] }}件</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">期限切れ</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $stats['expired'] }}件</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">未入金総額</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ number_format($stats['total_amount_pending']) }}円</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 検索・フィルターパネル -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.bank-transfers.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <!-- 検索フィールド -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">検索</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="注文番号、顧客名など">
                    </div>
                    
                    <!-- ステータスフィルター -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ステータス</label>
                        <select name="status" id="status" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">すべてのステータス</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>振込待ち</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>確認済み</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>期限切れ</option>
                        </select>
                    </div>
                    
                    <!-- 期限フィルター -->
                    <div>
                        <label for="deadline_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">期限</label>
                        <select name="deadline_filter" id="deadline_filter" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">すべて</option>
                            <option value="expired" {{ request('deadline_filter') == 'expired' ? 'selected' : '' }}>期限切れ</option>
                            <option value="today" {{ request('deadline_filter') == 'today' ? 'selected' : '' }}>本日期限</option>
                            <option value="this_week" {{ request('deadline_filter') == 'this_week' ? 'selected' : '' }}>今週期限</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        検索・フィルター
                    </button>
                    
                    @if(request('search') || request('status') || request('deadline_filter'))
                        <a href="{{ route('admin.bank-transfers.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                            フィルターをクリア
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- アクションボタン -->
    <div class="mb-6">
        <form action="{{ route('admin.bank-transfers.bulk-mark-expired') }}" method="POST" class="inline-block">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                onclick="return confirm('期限切れの振込をすべて処理しますか？この操作は元に戻せません。')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                一括期限切れ処理
            </button>
        </form>
    </div>

    <!-- 振込リスト -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        注文番号
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        顧客
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        振込金額
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        振込期限
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        ステータス
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        確認日時
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        アクション
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($bankTransfers as $transfer)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $transfer->order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                {{ $transfer->order->user->name ?? '削除されたユーザー' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $transfer->order->user->email ?? '' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ number_format($transfer->transfer_amount) }}円
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="{{ $transfer->isExpired() ? 'text-red-600 dark:text-red-400 font-semibold' : '' }}">
                                {{ $transfer->transfer_deadline->format('Y/m/d H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($transfer->transfer_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($transfer->transfer_status === 'expired') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                @if($transfer->transfer_status === 'pending') 振込待ち
                                @elseif($transfer->transfer_status === 'confirmed') 確認済み
                                @elseif($transfer->transfer_status === 'expired') 期限切れ
                                @else {{ $transfer->transfer_status }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($transfer->transfer_confirmed_at)
                                {{ $transfer->transfer_confirmed_at->format('Y/m/d H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.bank-transfers.show', $transfer) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">
                                詳細
                            </a>
                            @if($transfer->transfer_status === 'pending')
                                <form action="{{ route('admin.bank-transfers.confirm', $transfer) }}" method="POST" class="inline-block mr-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300" 
                                        onclick="return confirm('振込を確認済みにしますか？')">
                                        確認
                                    </button>
                                </form>
                                <form action="{{ route('admin.bank-transfers.mark-expired', $transfer) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300" 
                                        onclick="return confirm('期限切れにしますか？')">
                                        期限切れ
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            銀行振込が見つかりませんでした。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- ページネーション -->
    <div class="mt-4">
        {{ $bankTransfers->withQueryString()->links() }}
    </div>
@endsection 