@extends('layouts.admin')

@section('header', 'ショップ基本情報設定')

@section('content')
<!-- パンくずリスト -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.shop-settings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="mr-1 h-4 w-4 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                ショップ設定
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">基本情報設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.basic-info.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="space-y-6">
        <!-- 店舗情報セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">店舗情報</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 事業者名 -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            事業者名
                        </label>
                        <input type="text" name="company_name" id="company_name"
                            value="{{ old('company_name', $settings['company_name']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company_name') border-red-300 @enderror">
                        @error('company_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 事業者名(カナ) -->
                    <div>
                        <label for="company_name_kana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            事業者名(カナ)
                        </label>
                        <input type="text" name="company_name_kana" id="company_name_kana"
                            value="{{ old('company_name_kana', $settings['company_name_kana']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company_name_kana') border-red-300 @enderror">
                        @error('company_name_kana')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ショップ名 -->
                    <div>
                        <label for="shop_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ショップ名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="shop_name" id="shop_name"
                            value="{{ old('shop_name', $settings['shop_name']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('shop_name') border-red-300 @enderror"
                            required>
                        @error('shop_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ショップ名(カナ) -->
                    <div>
                        <label for="shop_name_kana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ショップ名(カナ)
                        </label>
                        <input type="text" name="shop_name_kana" id="shop_name_kana"
                            value="{{ old('shop_name_kana', $settings['shop_name_kana']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('shop_name_kana') border-red-300 @enderror">
                        @error('shop_name_kana')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ショップロゴ -->
                    <div>
                        <label for="shop_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ショップロゴ
                        </label>
                        <div class="mt-1 flex items-center space-x-4">
                            @if(!empty($settings['shop_logo']))
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $settings['shop_logo']) }}" alt="現在のロゴ" class="h-16 w-auto object-contain">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="shop_logo" id="shop_logo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 dark:hover:file:bg-indigo-800 @error('shop_logo') border-red-300 @enderror">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    JPEG、PNG、JPG、GIF形式（最大2MB）
                                </p>
                            </div>
                        </div>
                        @error('shop_logo')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 住所 -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            住所
                        </label>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- 郵便番号 -->
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    郵便番号
                                </label>
                                <div class="mt-1 flex">
                                    <input type="text" name="postal_code" id="postal_code"
                                        value="{{ old('postal_code', $settings['postal_code']) }}"
                                        class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('postal_code') border-red-300 @enderror"
                                        placeholder="例: 123-4567"
                                        maxlength="8">
                                    <button type="button" id="search-address" class="ml-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                        住所検索
                                    </button>
                                </div>
                                @error('postal_code')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 都道府県 -->
                            <div>
                                <label for="prefecture" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    都道府県
                                </label>
                                <select name="prefecture" id="prefecture"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('prefecture') border-red-300 @enderror">
                                    <option value="">選択してください</option>
                                    @php
                                    $prefectures = [
                                    '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
                                    '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
                                    '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
                                    '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
                                    '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
                                    '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
                                    '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
                                    ];
                                    @endphp
                                    @foreach($prefectures as $pref)
                                    <option value="{{ $pref }}" {{ old('prefecture', $settings['prefecture']) == $pref ? 'selected' : '' }}>
                                        {{ $pref }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('prefecture')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 市区町村名 -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    市区町村名
                                </label>
                                <input type="text" name="city" id="city"
                                    value="{{ old('city', $settings['city']) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('city') border-red-300 @enderror"
                                    placeholder="例: 渋谷区">
                                @error('city')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 番地・ビル名 -->
                            <div>
                                <label for="address_line" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    番地・ビル名
                                </label>
                                <input type="text" name="address_line" id="address_line"
                                    value="{{ old('address_line', $settings['address_line']) }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('address_line') border-red-300 @enderror"
                                    placeholder="例: 1-2-3 テックビル4F">
                                @error('address_line')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- 電話番号 -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            電話番号
                        </label>
                        <input type="text" name="phone_number" id="phone_number"
                            value="{{ old('phone_number', $settings['phone_number']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone_number') border-red-300 @enderror">
                        @error('phone_number')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 店舗営業時間 -->
                    <div>
                        <label for="business_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            店舗営業時間
                        </label>
                        <input type="text" name="business_hours" id="business_hours"
                            value="{{ old('business_hours', $settings['business_hours']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('business_hours') border-red-300 @enderror"
                            placeholder="例: 平日 9:00-18:00、土日 10:00-17:00">
                        @error('business_hours')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <!-- ショップ説明文 -->
                    <div>
                        <label for="shop_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            ショップ説明文
                        </label>
                        <textarea name="shop_description" id="shop_description" rows="4"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('shop_description') border-red-300 @enderror"
                            placeholder="ショップの特徴や方針について入力してください">{{ old('shop_description', $settings['shop_description']) }}</textarea>
                        @error('shop_description')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 会員設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">会員設定</h3>

                <div class="space-y-4">
                    <!-- ゲスト購入 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ゲスト購入
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="guest_purchase_enabled" value="1"
                                    {{ old('guest_purchase_enabled', $settings['guest_purchase_enabled'] ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="guest_purchase_enabled" value="0"
                                    {{ old('guest_purchase_enabled', $settings['guest_purchase_enabled'] ? '1' : '0') === '0' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">無効</span>
                            </label>
                        </div>
                    </div>

                    <!-- お気に入り商品 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            お気に入り商品
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="favorite_enabled" value="1"
                                    {{ old('favorite_enabled', $settings['favorite_enabled'] ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="favorite_enabled" value="0"
                                    {{ old('favorite_enabled', $settings['favorite_enabled'] ? '1' : '0') === '0' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">無効</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 商品設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">商品設定</h3>

                <!-- 在庫切れ商品の表示 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        在庫切れ商品の表示
                    </label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="show_out_of_stock" value="1"
                                {{ old('show_out_of_stock', $settings['show_out_of_stock'] ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="show_out_of_stock" value="0"
                                {{ old('show_out_of_stock', $settings['show_out_of_stock'] ? '1' : '0') === '0' ? 'checked' : '' }}
                                class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">無効</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- 送料設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">送料設定</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 送料無料条件（金額） -->
                    <div>
                        <label for="free_shipping_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            送料無料条件（金額）
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="free_shipping_amount" id="free_shipping_amount"
                                value="{{ old('free_shipping_amount', $settings['free_shipping_amount']) }}"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('free_shipping_amount') border-red-300 @enderror"
                                min="0" step="1" placeholder="例: 5000">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">円</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            この金額以上の購入で送料無料（0円で無効）
                        </p>
                        @error('free_shipping_amount')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 送料無料条件（数量） -->
                    <div>
                        <label for="free_shipping_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            送料無料条件（数量）
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="free_shipping_quantity" id="free_shipping_quantity"
                                value="{{ old('free_shipping_quantity', $settings['free_shipping_quantity']) }}"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('free_shipping_quantity') border-red-300 @enderror"
                                min="0" step="1" placeholder="例: 3">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">個</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            この個数以上の購入で送料無料（0個で無効）
                        </p>
                        @error('free_shipping_quantity')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 税設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">税設定</h3>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- 消費税率 -->
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            消費税率（%） <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" name="tax_rate" id="tax_rate"
                                value="{{ old('tax_rate', $settings['tax_rate'] ?? 10) }}"
                                class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('tax_rate') border-red-300 @enderror"
                                min="0" max="100" step="1" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                        @error('tax_rate')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 価格表示方式 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            価格表示方式
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="tax_included" id="tax_included"
                                    {{ old('tax_included', $settings['tax_included'] ?? false) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                                <label for="tax_included" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    税込表示（チェックを外すと税抜表示）
                                </label>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            商品価格の表示方式を選択してください
                        </p>
                    </div>
                </div>

                <!-- 適格請求書発行事業者登録番号 -->
                <div>
                    <label for="invoice_registration_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        適格請求書発行事業者登録番号
                    </label>
                    <input type="text" name="invoice_registration_number" id="invoice_registration_number"
                        value="{{ old('invoice_registration_number', $settings['invoice_registration_number']) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('invoice_registration_number') border-red-300 @enderror"
                        placeholder="例: T1234567890123">
                    @error('invoice_registration_number')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ポイント設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">ポイント設定</h3>

                <div class="space-y-4">
                    <!-- ポイント機能 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ポイント機能
                        </label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="point_enabled" value="1"
                                    {{ old('point_enabled', $settings['point_enabled'] ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="point_enabled" value="0"
                                    {{ old('point_enabled', $settings['point_enabled'] ? '1' : '0') === '0' ? 'checked' : '' }}
                                    class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">無効</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- ポイント付与率 -->
                        <div>
                            <label for="point_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                ポイント付与率
                            </label>
                            <input type="text" name="point_rate" id="point_rate"
                                value="{{ old('point_rate', $settings['point_rate']) }}"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('point_rate') border-red-300 @enderror"
                                placeholder="例: 1（1%）">
                            @error('point_rate')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ポイント換算レート -->
                        <div>
                            <label for="point_conversion_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                ポイント換算レート
                            </label>
                            <input type="text" name="point_conversion_rate" id="point_conversion_rate"
                                value="{{ old('point_conversion_rate', $settings['point_conversion_rate']) }}"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('point_conversion_rate') border-red-300 @enderror"
                                placeholder="例: 1（1ポイント=1円）">
                            @error('point_conversion_rate')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Googleアナリティクス設定セクション -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Googleアナリティクス設定</h3>

                <!-- トラッキングID -->
                <div>
                    <label for="google_analytics_tracking_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        トラッキングID
                    </label>
                    <input type="text" name="google_analytics_tracking_id" id="google_analytics_tracking_id"
                        value="{{ old('google_analytics_tracking_id', $settings['google_analytics_tracking_id']) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('google_analytics_tracking_id') border-red-300 @enderror"
                        placeholder="例: G-XXXXXXXXXX">
                    @error('google_analytics_tracking_id')
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const postalCodeInput = document.getElementById('postal_code');
        const searchButton = document.getElementById('search-address');
        const prefectureSelect = document.getElementById('prefecture');
        const cityInput = document.getElementById('city');

        // 郵便番号の自動フォーマット（ハイフン自動挿入）
        postalCodeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value.length > 3) {
                value = value.slice(0, 3) + '-' + value.slice(3, 7);
            }
            e.target.value = value;
        });

        // 住所検索ボタンのクリックイベント
        searchButton.addEventListener('click', function() {
            const postalCode = postalCodeInput.value.replace(/[^\d]/g, '');

            if (postalCode.length !== 7) {
                alert('正しい郵便番号を入力してください（例: 123-4567）');
                return;
            }

            // ローディング状態を表示
            searchButton.disabled = true;
            searchButton.textContent = '検索中...';

            // 郵便番号APIを使用して住所を取得
            fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${postalCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200 && data.results && data.results.length > 0) {
                        const result = data.results[0];

                        // 都道府県を設定
                        prefectureSelect.value = result.address1;

                        // 市区町村を設定
                        cityInput.value = result.address2 + result.address3;

                        // 成功メッセージ
                        showMessage('住所を自動入力しました', 'success');
                    } else {
                        showMessage('該当する住所が見つかりませんでした', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('住所の取得に失敗しました', 'error');
                })
                .finally(() => {
                    // ローディング状態を解除
                    searchButton.disabled = false;
                    searchButton.textContent = '住所検索';
                });
        });

        // Enterキーでも住所検索を実行
        postalCodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchButton.click();
            }
        });

        // メッセージ表示関数
        function showMessage(message, type) {
            // 既存のメッセージを削除
            const existingMessage = document.querySelector('.address-search-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            // 新しいメッセージを作成
            const messageDiv = document.createElement('div');
            messageDiv.className = `address-search-message mt-2 p-2 rounded text-sm ${
                    type === 'success' 
                        ? 'bg-green-100 text-green-700 border border-green-300' 
                        : 'bg-red-100 text-red-700 border border-red-300'
                }`;
            messageDiv.textContent = message;

            // 郵便番号入力欄の下に挿入
            postalCodeInput.parentNode.parentNode.appendChild(messageDiv);

            // 3秒後にメッセージを削除
            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }
    });
</script>
@endsection
@endsection