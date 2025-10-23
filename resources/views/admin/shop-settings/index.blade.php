@extends('layouts.admin')

@section('header', 'ショップ設定')

@section('content')
<div class="space-y-6">
    <!-- ショップ設定メニューカード -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">ショップ設定</h2>
                <p class="text-gray-600 dark:text-gray-400">ショップの各種設定と管理機能にアクセスできます。</p>
            </div>

            <!-- メニューグリッド -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 基本情報設定 -->
                <a href="{{ route('admin.shop-settings.basic-info') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">基本情報設定</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        ショップ名、問い合わせ先、営業時間などの基本情報を設定できます。
                    </p>
                </a>

                <!-- 配送設定 -->
                <a href="{{ route('admin.shop-settings.shipping') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">配送設定</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        配送料金、配送業者、配送日数などの設定を管理できます。
                    </p>
                </a>

                <!-- 決済設定 -->
                <a href="{{ route('admin.shop-settings.payment') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">決済設定</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        決済方法、税率、価格表示などの設定を管理できます。
                    </p>
                </a>

                <!-- 特定商取引法表記 -->
                <a href="{{ route('admin.shop-settings.legal') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">特定商取引法表記</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        特定商取引法に基づく表記の設定を管理できます。
                    </p>
                </a>

                <!-- プライバシーポリシー -->
                <a href="{{ route('admin.shop-settings.privacy') }}" class="block p-6 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium">プライバシーポリシー</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        個人情報の取扱いに関する基本方針の設定を管理できます。
                    </p>
                </a>
            </div>
        </div>
    </div>
    
</div>
@endsection 