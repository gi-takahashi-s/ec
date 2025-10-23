@extends('layouts.admin')

@section('header')
メンテナンスモード
@endsection

@section('content')
<!-- パンくずリスト -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.system.info') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="mr-1 h-4 w-4 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                システム設定
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">メンテナンスモード</span>
            </div>
        </li>
    </ol>
</nav>

<!-- 注意事項 -->
<div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-gray-800 dark:text-gray-200">フロント画面メンテナンスモードについて</h3>
            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                <ul class="list-disc list-inside space-y-1">
                    <li>フロント画面のメンテナンスモードを有効にすると、一般ユーザーはフロント画面にアクセスできなくなります</li>
                    <li><strong>管理画面は常にアクセス可能</strong>で、管理者は引き続き操作できます</li>
                    <li>シークレットキーを設定した場合、URLに ?secret=キー を付けることで一般ユーザーもフロント画面にアクセス可能になります</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">
    <!-- メンテナンスモード状態 -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">メンテナンスモード設定</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">状態:</span>
                    <span class="px-3 py-1 text-sm rounded-full {{ $isMaintenanceMode ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $isMaintenanceMode ? 'メンテナンス中' : '通常運用中' }}
                    </span>
                </div>
            </div>

            @if($isMaintenanceMode && !empty($maintenanceData))
            <div class="rounded-lg mb-6">
                <p class="text-sm mb-4">開始時刻: {{ date('Y-m-d H:i:s', $maintenanceData['time']) }}</p>

                <!-- メンテナンス更新フォーム -->
                <form method="POST" action="{{ route('admin.system.maintenance.toggle') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" value="update">

                    <div>
                        <label for="message_update" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            メンテナンスメッセージ
                        </label>
                        <textarea id="message_update" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" placeholder="メンテナンス中に表示するメッセージを入力してください">{{ old('message', $maintenanceData['message'] ?? '') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time_update" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            終了予定時刻
                        </label>
                        <input type="datetime-local" id="end_time_update" name="end_time" value="{{ old('end_time', isset($maintenanceData['end_time']) && !empty($maintenanceData['end_time']) ? date('Y-m-d\TH:i', strtotime($maintenanceData['end_time'])) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        @error('end_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4 pt-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            メンテナンス設定を更新
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- メンテナンスモード切り替えフォーム -->
            @if($isMaintenanceMode)
            <!-- 解除フォーム -->
            <form method="POST" action="{{ route('admin.system.maintenance.toggle') }}" class="mb-6">
                @csrf
                <input type="hidden" name="action" value="disable">
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        メンテナンスモードを解除
                    </button>
                </div>
            </form>
            @else
            <!-- 有効化フォーム -->
            <form method="POST" action="{{ route('admin.system.maintenance.toggle') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="action" value="enable">

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            メンテナンスメッセージ（任意）
                        </label>
                        <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" placeholder="メンテナンス中に表示するメッセージを入力してください&#10;改行も反映されます">{{ old('message') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">改行を含むメッセージを入力できます</p>
                        @error('message')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            終了予定時刻（任意）
                        </label>
                        <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">メンテナンス終了予定時刻を設定できます</p>
                        @error('end_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        バイパス用シークレット（任意）
                    </label>
                    <input type="text" id="secret" name="secret" value="{{ old('secret') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm" placeholder="メンテナンス中でもアクセス可能にするシークレットキー">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URLに ?secret=キー を付けることでメンテナンス中でもアクセス可能になります</p>
                    @error('secret')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        メンテナンスモードを有効にする
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>

</div>
@endsection