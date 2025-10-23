@extends('layouts.admin')

@section('header', 'プライバシーポリシー設定')

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
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">プライバシーポリシー設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.privacy.update') }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="space-y-6">
        <!-- 基本事項 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">基本事項</h3>

                <div class="space-y-6">
                    <!-- 事業者名 -->
                    <div>
                        <label for="privacy_company_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            事業者名 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="privacy_company_name" id="privacy_company_name"
                            value="{{ old('privacy_company_name', $settings['privacy_company_name']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('privacy_company_name') border-red-300 @enderror"
                            required>
                        @error('privacy_company_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 最終更新日 -->
                    <div>
                        <label for="privacy_updated_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            最終更新日 <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="privacy_updated_date" id="privacy_updated_date"
                            value="{{ old('privacy_updated_date', $settings['privacy_updated_date']) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('privacy_updated_date') border-red-300 @enderror"
                            required>
                        @error('privacy_updated_date')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 個人情報の収集・利用目的 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">個人情報の収集・利用目的</h3>

                <div>
                    <label for="collection_purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        収集・利用目的 <span class="text-red-500">*</span>
                    </label>
                    <textarea name="collection_purpose" id="collection_purpose" rows="8"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('collection_purpose') border-red-300 @enderror"
                        placeholder="例:&#10;・商品の発送やお客様へのご連絡のため&#10;・商品代金の請求のため&#10;・お客様からのお問い合わせへの対応のため&#10;・マーケティング活動のため&#10;・その他当社のサービス提供に付随する業務のため"
                        required>{{ old('collection_purpose', $settings['collection_purpose']) }}</textarea>
                    @error('collection_purpose')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 収集する個人情報の項目 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">収集する個人情報の項目</h3>

                <div>
                    <label for="collected_information" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        収集項目 <span class="text-red-500">*</span>
                    </label>
                    <textarea name="collected_information" id="collected_information" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('collected_information') border-red-300 @enderror"
                        placeholder="例:&#10;・氏名&#10;・住所&#10;・電話番号&#10;・メールアドレス&#10;・その他お客様が入力された情報"
                        required>{{ old('collected_information', $settings['collected_information']) }}</textarea>
                    @error('collected_information')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 個人情報の第三者提供 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">個人情報の第三者提供</h3>

                <div>
                    <label for="third_party_provision" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        第三者提供について <span class="text-red-500">*</span>
                    </label>
                    <textarea name="third_party_provision" id="third_party_provision" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('third_party_provision') border-red-300 @enderror"
                        placeholder="例: 当社は、法令に基づく場合を除き、お客様の同意なく個人情報を第三者に提供することはありません。"
                        required>{{ old('third_party_provision', $settings['third_party_provision']) }}</textarea>
                    @error('third_party_provision')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 個人情報の管理・保護 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">個人情報の管理・保護</h3>

                <div>
                    <label for="information_management" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        管理・保護体制 <span class="text-red-500">*</span>
                    </label>
                    <textarea name="information_management" id="information_management" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('information_management') border-red-300 @enderror"
                        placeholder="例: 当社は、個人情報の紛失、破壊、改ざん及び漏洩などを防止するため、適切な安全管理措置を講じます。"
                        required>{{ old('information_management', $settings['information_management']) }}</textarea>
                    @error('information_management')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- お客様の権利 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">お客様の権利</h3>

                <div>
                    <label for="customer_rights" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        開示・訂正・削除等の権利について <span class="text-red-500">*</span>
                    </label>
                    <textarea name="customer_rights" id="customer_rights" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('customer_rights') border-red-300 @enderror"
                        placeholder="例: お客様は、当社が保有する個人情報について、開示、訂正、削除を求めることができます。ご希望の場合は、下記の連絡先までお問い合わせください。"
                        required>{{ old('customer_rights', $settings['customer_rights']) }}</textarea>
                    @error('customer_rights')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Cookie・アクセス解析 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Cookie・アクセス解析</h3>

                <div>
                    <label for="cookie_policy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Cookie使用について
                    </label>
                    <textarea name="cookie_policy" id="cookie_policy" rows="6"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('cookie_policy') border-red-300 @enderror"
                        placeholder="例: 当ウェブサイトでは、サービスの改善やお客様の利便性向上のため、Cookieを使用しています。また、Google Analyticsなどのアクセス解析ツールを使用して、ウェブサイトの利用状況を分析しています。">{{ old('cookie_policy', $settings['cookie_policy']) }}</textarea>
                    @error('cookie_policy')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- お問い合わせ先 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">お問い合わせ先</h3>

                <div>
                    <label for="contact_information" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        問い合わせ先情報 <span class="text-red-500">*</span>
                    </label>
                    <textarea name="contact_information" id="contact_information" rows="4"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('contact_information') border-red-300 @enderror"
                        placeholder="例:&#10;[会社名]&#10;メールアドレス: info@example.com&#10;電話番号: 000-0000-0000&#10;受付時間: 平日 9:00-18:00"
                        required>{{ old('contact_information', $settings['contact_information']) }}</textarea>
                    @error('contact_information')
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