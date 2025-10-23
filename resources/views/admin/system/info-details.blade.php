@extends('layouts.admin')

@section('header')
    システム情報詳細
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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">システム情報詳細</span>
            </div>
        </li>
    </ol>
</nav>

<div class="space-y-6">
    <!-- システム情報詳細カード -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">システム情報詳細</h2>
            </div>

            <!-- 基本情報 -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">基本情報</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">PHP バージョン:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['php_version'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Laravel バージョン:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['laravel_version'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">サーバーソフトウェア:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['server_software'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">データベース:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['database_version'] }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">環境設定</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">環境:</dt>
                            <dd class="text-sm font-medium">
                                <span class="px-2 py-1 text-xs rounded-full {{ $systemInfo['environment'] === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $systemInfo['environment'] }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">デバッグモード:</dt>
                            <dd class="text-sm font-medium">
                                <span class="px-2 py-1 text-xs rounded-full {{ $systemInfo['debug_mode'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $systemInfo['debug_mode'] ? 'ON' : 'OFF' }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">タイムゾーン:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['timezone'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">メンテナンスモード:</dt>
                            <dd class="text-sm font-medium">
                                <span class="px-2 py-1 text-xs rounded-full {{ $systemInfo['maintenance_mode'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $systemInfo['maintenance_mode'] ? 'ON' : 'OFF' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">PHP設定</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">メモリ制限:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['memory_limit'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">実行時間制限:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['max_execution_time'] }}秒</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">アップロード制限:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['upload_max_filesize'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">POST制限:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['post_max_size'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- ドライバー設定 -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">ドライバー設定</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">キャッシュ:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['cache_driver'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">セッション:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['session_driver'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">キュー:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['queue_driver'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">メール:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['mail_driver'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">ストレージ:</dt>
                            <dd class="text-sm font-medium">{{ $systemInfo['storage_disk'] }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">URL設定</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">アプリケーションURL:</dt>
                            <dd class="text-sm font-medium break-all">{{ $systemInfo['app_url'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- リソース使用状況 -->
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">ディスク使用量</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">使用量</span>
                            <span class="text-sm font-medium">{{ $diskUsage['used'] }} / {{ $diskUsage['total'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $diskUsage['percentage'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>使用率: {{ $diskUsage['percentage'] }}%</span>
                            <span>空き容量: {{ $diskUsage['free'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">メモリ使用量</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">現在の使用量:</dt>
                            <dd class="text-sm font-medium">{{ $memoryUsage['current'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">ピーク使用量:</dt>
                            <dd class="text-sm font-medium">{{ $memoryUsage['peak'] }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600 dark:text-gray-400">制限:</dt>
                            <dd class="text-sm font-medium">{{ $memoryUsage['limit'] }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 