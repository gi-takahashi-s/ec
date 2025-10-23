<x-app-layout>
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">プライバシーポリシー</h1>
                    
                    <div class="space-y-8">
                        @if($privacyInfo['privacy_company_name'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">事業者名</h2>
                            <p class="text-gray-700">{{ $privacyInfo['privacy_company_name'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['privacy_updated_date'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">最終更新日</h2>
                            <p class="text-gray-700">{{ $privacyInfo['privacy_updated_date'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['collection_purpose'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">個人情報の収集・利用目的</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['collection_purpose'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['collected_information'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">収集する個人情報の項目</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['collected_information'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['third_party_provision'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">第三者への提供について</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['third_party_provision'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['information_management'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">個人情報の管理・保護</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['information_management'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['customer_rights'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">お客様の権利</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['customer_rights'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['cookie_policy'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">Cookie・アクセス解析について</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['cookie_policy'] }}</p>
                        </div>
                        @endif

                        @if($privacyInfo['contact_information'])
                        <div class="pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">お問い合わせ先</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $privacyInfo['contact_information'] }}</p>
                        </div>
                        @endif

                        @if(!$privacyInfo['privacy_company_name'] && !$privacyInfo['privacy_updated_date'] && !$privacyInfo['collection_purpose'] && !$privacyInfo['collected_information'] && !$privacyInfo['third_party_provision'] && !$privacyInfo['information_management'] && !$privacyInfo['customer_rights'] && !$privacyInfo['cookie_policy'] && !$privacyInfo['contact_information'])
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-500 mb-2">プライバシーポリシーが設定されていません</h3>
                            <p class="text-gray-400">管理画面からプライバシーポリシーの設定を行ってください。</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            トップページに戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 