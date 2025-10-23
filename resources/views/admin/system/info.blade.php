@extends('layouts.admin')

@section('header')
    システム設定
@endsection

@section('content')
<div class="space-y-6">
    <!-- システム設定メニューカード -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">システム設定</h2>
                <p class="text-gray-600 dark:text-gray-400">システムの各種設定と管理機能にアクセスできます。</p>
            </div>

            <!-- メニューグリッド -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                

                <!-- メンテナンスモード -->
                <a href="{{ route('admin.system.maintenance') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">メンテナンスモード</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        サイトのメンテナンスモードの有効/無効を切り替え、メンテナンス画面の設定を管理できます。
                    </p>
                </a>

                <!-- ログイン履歴 -->
                <a href="{{ route('admin.system.login-history') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">ログイン履歴</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        ユーザーのログイン履歴を確認し、成功・失敗の統計情報やフィルタリング機能を利用できます。
                    </p>
                </a>

                <!-- セキュリティ監視 -->
                <a href="{{ route('admin.system.security') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">セキュリティ監視</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        セキュリティ統計、失敗ログイン監視、疑わしいIPアドレスの検出などを管理できます。
                    </p>
                </a>

                <!-- セキュリティ設定 -->
                <a href="{{ route('admin.system.security-settings') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">セキュリティ設定</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        管理画面URL設定、IP制限（許可・拒否リスト）の設定を管理できます。
                    </p>
                </a>

                <!-- システム情報詳細 -->
                <a href="{{ route('admin.system.info.details') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">システム情報</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        サーバー環境、PHP設定、データベース情報、リソース使用状況などの詳細情報を確認できます。
                    </p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 