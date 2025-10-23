@extends('layouts.admin')

@section('header')
    セキュリティ設定
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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">セキュリティ設定</span>
            </div>
        </li>
    </ol>
</nav>

<div class="space-y-6">

    <!-- 注意事項 -->
    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    重要な注意事項
                </h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <ul class="list-disc list-inside space-y-1">
                        <li>管理画面URLを変更した場合、現在のURLでアクセスできなくなります。</li>
                        <li>IP制限を設定する際は、現在のIPアドレスが許可リストに含まれていることを確認してください。</li>
                        <li>誤った設定により管理画面にアクセスできなくなった場合は、サーバー管理者にお問い合わせください。</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- セキュリティ設定フォーム -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">セキュリティ設定</h2>
                <p class="text-gray-600 dark:text-gray-400">管理画面のURL設定とIP制限を管理できます。</p>
            </div>

            <form method="POST" action="{{ url(ltrim(config('app.admin_url', '/admin'), '/') . '/system/security-settings') }}" class="space-y-8">
                @csrf

                <!-- 管理画面URL設定 -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">管理画面URL設定</h3>
                    <div>
                        <label for="admin_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            管理画面URL
                        </label>
                        <input type="text" 
                               id="admin_url" 
                               name="admin_url" 
                               value="{{ old('admin_url', $settings['admin_url']) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                               placeholder="/admin"
                               required>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            管理画面にアクセスするためのURLパスを設定します。（例: /admin, /manage）
                        </p>
                        @error('admin_url')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 管理画面IP制限設定 -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">管理画面IP制限設定</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="admin_ip_allow_list" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IP制限（許可リスト）
                            </label>
                            <textarea id="admin_ip_allow_list" 
                                      name="admin_ip_allow_list" 
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="192.168.1.1&#10;10.0.0.0/24&#10;203.0.113.0/24">{{ old('admin_ip_allow_list', $settings['admin_ip_allow_list']) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                管理画面へのアクセスを許可するIPアドレスを1行に1つずつ入力してください。CIDR記法も使用可能です。
                            </p>
                            @error('admin_ip_allow_list')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_ip_deny_list" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IP制限（拒否リスト）
                            </label>
                            <textarea id="admin_ip_deny_list" 
                                      name="admin_ip_deny_list" 
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="192.168.1.100&#10;10.0.0.50/32&#10;203.0.113.200">{{ old('admin_ip_deny_list', $settings['admin_ip_deny_list']) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                管理画面へのアクセスを拒否するIPアドレスを1行に1つずつ入力してください。CIDR記法も使用可能です。
                            </p>
                            @error('admin_ip_deny_list')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- フロント画面IP制限設定 -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">フロント画面IP制限設定</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="frontend_ip_allow_list" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IP制限（許可リスト）
                            </label>
                            <textarea id="frontend_ip_allow_list" 
                                      name="frontend_ip_allow_list" 
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="192.168.1.1&#10;10.0.0.0/24&#10;203.0.113.0/24">{{ old('frontend_ip_allow_list', $settings['frontend_ip_allow_list']) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                フロント画面へのアクセスを許可するIPアドレスを1行に1つずつ入力してください。CIDR記法も使用可能です。
                            </p>
                            @error('frontend_ip_allow_list')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="frontend_ip_deny_list" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IP制限（拒否リスト）
                            </label>
                            <textarea id="frontend_ip_deny_list" 
                                      name="frontend_ip_deny_list" 
                                      rows="5"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                      placeholder="192.168.1.100&#10;10.0.0.50/32&#10;203.0.113.200">{{ old('frontend_ip_deny_list', $settings['frontend_ip_deny_list']) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                フロント画面へのアクセスを拒否するIPアドレスを1行に1つずつ入力してください。CIDR記法も使用可能です。
                            </p>
                            @error('frontend_ip_deny_list')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 保存ボタン -->
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        設定を保存
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection 
