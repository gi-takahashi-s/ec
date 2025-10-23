@extends('layouts.admin')

@section('header', '銀行振込決済設定')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">銀行振込設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.payment.method.update', 'bank_transfer') }}" method="POST">
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
                                銀行振込決済を有効にする
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                チェックを入れると、お客様が銀行振込決済を選択できるようになります
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 振込設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">振込設定</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 振込期限 -->
                    <div>
                        <label for="deadline_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            振込期限（日） <span class="text-red-500">*</span>
                        </label>
                        <select name="settings[deadline_days]" id="deadline_days"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.deadline_days') border-red-300 @enderror"
                            required>
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" {{ old('settings.deadline_days', $paymentMethod->settings['deadline_days'] ?? 7) == $i ? 'selected' : '' }}>
                                {{ $i }}日後
                                </option>
                                @endfor
                        </select>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            注文日から振込期限までの日数
                        </p>
                        @error('settings.deadline_days')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 振込手数料 -->
                    <div>
                        <label for="transfer_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            振込手数料（円）
                        </label>
                        <input type="number" name="settings[transfer_fee]" id="transfer_fee"
                            value="{{ old('settings.transfer_fee', $paymentMethod->settings['transfer_fee'] ?? 0) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.transfer_fee') border-red-300 @enderror"
                            min="0">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            お客様負担の振込手数料（0円の場合は手数料なし）
                        </p>
                        @error('settings.transfer_fee')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 振込先銀行情報 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">振込先銀行情報</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 銀行名 -->
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            銀行名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="settings[bank_name]" id="bank_name"
                            value="{{ old('settings.bank_name', $paymentMethod->settings['bank_name'] ?? '') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.bank_name') border-red-300 @enderror"
                            required>
                        @error('settings.bank_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 支店名 -->
                    <div>
                        <label for="bank_branch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            支店名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="settings[bank_branch]" id="bank_branch"
                            value="{{ old('settings.bank_branch', $paymentMethod->settings['bank_branch'] ?? '') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.bank_branch') border-red-300 @enderror"
                            required>
                        @error('settings.bank_branch')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 口座種別 -->
                    <div>
                        <label for="account_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            口座種別 <span class="text-red-500">*</span>
                        </label>
                        <select name="settings[account_type]" id="account_type"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.account_type') border-red-300 @enderror"
                            required>
                            <option value="">選択してください</option>
                            <option value="普通" {{ old('settings.account_type', $paymentMethod->settings['account_type'] ?? '') == '普通' ? 'selected' : '' }}>普通</option>
                            <option value="当座" {{ old('settings.account_type', $paymentMethod->settings['account_type'] ?? '') == '当座' ? 'selected' : '' }}>当座</option>
                        </select>
                        @error('settings.account_type')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 口座番号 -->
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            口座番号 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="settings[account_number]" id="account_number"
                            value="{{ old('settings.account_number', $paymentMethod->settings['account_number'] ?? '') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.account_number') border-red-300 @enderror"
                            required>
                        @error('settings.account_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 口座名義 -->
                    <div class="sm:col-span-2">
                        <label for="account_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            口座名義 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="settings[account_name]" id="account_name"
                            value="{{ old('settings.account_name', $paymentMethod->settings['account_name'] ?? '') }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.account_name') border-red-300 @enderror"
                            required>
                        @error('settings.account_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 注意事項設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">注意事項設定</h3>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        お客様への注意事項
                    </label>
                    <textarea name="settings[notes]" id="notes" rows="4"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.notes') border-red-300 @enderror"
                        placeholder="振込時の注意事項をご記入ください">{{ old('settings.notes', $paymentMethod->settings['notes'] ?? "・振込名義は注文者名と同じにしてください。\n・振込手数料はお客様負担となります。\n・入金確認後に商品を発送いたします。") }}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        注文確認画面やメールに表示される注意事項
                    </p>
                    @error('settings.notes')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
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