@extends('layouts.admin')

@section('header', 'クレジットカード決済（Stripe）設定')

@section('content')
<!-- パンくずリスト -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.shop-settings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                ショップ設定
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <a href="{{ route('admin.shop-settings.payment') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">決済設定</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Stripe設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.payment.method.update', 'stripe') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="space-y-6">
        <!-- 基本設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">基本設定</h3>

                <div class="space-y-4">
                    <!-- 有効/無効 -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_enabled" id="is_enabled"
                                {{ $paymentMethod->is_enabled ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3">
                            <label for="is_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                クレジットカード決済を有効にする
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                チェックを入れると、お客様がクレジットカード決済を選択できるようになります
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stripe設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Stripe設定</h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- テストモード -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            動作モード
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="radio" name="settings[test_mode]" id="test_mode_on" value="1"
                                    {{ (old('settings.test_mode', $paymentMethod->settings['test_mode'] ?? true)) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <label for="test_mode_on" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    テストモード（推奨）
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="settings[test_mode]" id="test_mode_off" value="0"
                                    {{ !(old('settings.test_mode', $paymentMethod->settings['test_mode'] ?? true)) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800">
                                <label for="test_mode_off" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    本番モード
                                </label>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            テストモードでは実際の決済は行われません。本番運用前に必ずテストを行ってください。
                        </p>
                    </div>

                    <!-- 手数料設定 -->
                    <div>
                        <label for="fee_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            決済手数料率（%）
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="settings[fee_rate]" id="fee_rate"
                                value="{{ old('settings.fee_rate', $paymentMethod->settings['fee_rate'] ?? '3.6') }}"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.fee_rate') border-red-300 @enderror"
                                min="0" max="100" step="0.1">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Stripeの決済手数料率を設定してください（通常3.6%）
                        </p>
                        @error('settings.fee_rate')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 環境設定情報 -->
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                        環境設定ファイル（.env）での設定が必要です
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p>Stripeの詳細設定は環境設定ファイル（.env）で行ってください：</p>
                        <ul class="list-disc pl-5 mt-2">
                            <li><strong>STRIPE_KEY</strong>：公開キー（pk_test_... または pk_live_...）</li>
                            <li><strong>STRIPE_SECRET</strong>：シークレットキー（sk_test_... または sk_live_...）</li>
                            <li><strong>STRIPE_WEBHOOK_SECRET</strong>：Webhookシークレット（whsec_...）</li>
                        </ul>
                        <p class="mt-2">これらの値はStripeダッシュボードから取得できます。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end space-x-3">
        <a href="{{ route('admin.shop-settings.payment') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            キャンセル
        </a>
        <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            保存する
        </button>
    </div>
</form>
@endsection 