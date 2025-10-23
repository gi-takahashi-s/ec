@extends('layouts.admin')

@section('header', '特定商取引法表記設定')

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
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">特定商取引法表記設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.legal.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="space-y-6">
        <!-- 事業者情報 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">事業者情報</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 販売業者名 -->
                    <div class="sm:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            販売業者名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" id="company_name"
                            value="{{ old('company_name', $settings['company_name']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company_name') border-red-300 @enderror"
                            required>
                        @error('company_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 代表者名 -->
                    <div>
                        <label for="representative_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            代表者名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="representative_name" id="representative_name"
                            value="{{ old('representative_name', $settings['representative_name']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('representative_name') border-red-300 @enderror"
                            required>
                        @error('representative_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 電話番号 -->
                    <div>
                        <label for="company_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            電話番号 <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="company_phone" id="company_phone"
                            value="{{ old('company_phone', $settings['company_phone']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company_phone') border-red-300 @enderror"
                            required>
                        @error('company_phone')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <!-- 所在地 -->
                    <div>
                        <label for="company_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            所在地 <span class="text-red-500">*</span>
                        </label>
                        <textarea name="company_address" id="company_address" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company_address') border-red-300 @enderror"
                            placeholder="〒000-0000 ○○県○○市○○区○○ 1-2-3 ○○ビル4F"
                            required>{{ old('company_address', $settings['company_address']) }}</textarea>
                        @error('company_address')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 商品・価格情報 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">商品・価格情報</h3>

                <div class="space-y-6">
                    <!-- 商品以外の必要料金 -->
                    <div>
                        <label for="additional_charges" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            商品以外の必要料金
                        </label>
                        <textarea name="additional_charges" id="additional_charges" rows="4"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('additional_charges') border-red-300 @enderror"
                            placeholder="例: 送料、消費税、振込手数料など">{{ old('additional_charges', $settings['additional_charges']) }}</textarea>
                        @error('additional_charges')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 代金の支払時期 -->
                    <div>
                        <label for="payment_timing" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            代金の支払時期
                        </label>
                        <textarea name="payment_timing" id="payment_timing" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('payment_timing') border-red-300 @enderror"
                            placeholder="例: クレジットカード決済の場合は注文確定時、銀行振込の場合は注文確定後○日以内">{{ old('payment_timing', $settings['payment_timing']) }}</textarea>
                        @error('payment_timing')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 商品の引渡時期 -->
                    <div>
                        <label for="delivery_timing" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            商品の引渡時期
                        </label>
                        <textarea name="delivery_timing" id="delivery_timing" rows="3"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('delivery_timing') border-red-300 @enderror"
                            placeholder="例: 入金確認後○営業日以内に発送">{{ old('delivery_timing', $settings['delivery_timing']) }}</textarea>
                        @error('delivery_timing')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 返品・交換条件 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">返品・交換条件</h3>

                <div>
                    <label for="return_policy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        返品・交換の条件・返品に要する費用
                    </label>
                    <textarea name="return_policy" id="return_policy" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('return_policy') border-red-300 @enderror"
                        placeholder="例: 商品に不備がある場合、商品到着後○日以内にご連絡いただければ交換いたします。お客様都合による返品はお受けできません。返品送料はお客様負担となります。">{{ old('return_policy', $settings['return_policy']) }}</textarea>
                    @error('return_policy')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


    </div>

    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end space-x-3">
        <a href="{{ route('admin.shop-settings.index') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            キャンセル
        </a>
        <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            保存する
        </button>
    </div>
</form>
@endsection