<x-app-layout>
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">特定商取引法表記</h1>
                    
                    <div class="space-y-8">
                        @if($legalInfo['company_name'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">販売業者名</h2>
                            <p class="text-gray-700">{{ $legalInfo['company_name'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['representative_name'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">代表者名</h2>
                            <p class="text-gray-700">{{ $legalInfo['representative_name'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['company_address'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">所在地</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $legalInfo['company_address'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['company_phone'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">電話番号</h2>
                            <p class="text-gray-700">{{ $legalInfo['company_phone'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['additional_charges'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">商品以外の必要料金</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $legalInfo['additional_charges'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['payment_timing'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">代金の支払時期</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $legalInfo['payment_timing'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['delivery_timing'])
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">商品の引渡時期</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $legalInfo['delivery_timing'] }}</p>
                        </div>
                        @endif

                        @if($legalInfo['return_policy'])
                        <div class="pb-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-3">返品・交換の条件</h2>
                            <p class="text-gray-700 whitespace-pre-line">{{ $legalInfo['return_policy'] }}</p>
                        </div>
                        @endif

                        @if(!$legalInfo['company_name'] && !$legalInfo['representative_name'] && !$legalInfo['company_address'] && !$legalInfo['company_phone'] && !$legalInfo['additional_charges'] && !$legalInfo['payment_timing'] && !$legalInfo['delivery_timing'] && !$legalInfo['return_policy'])
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-500 mb-2">特定商取引法表記が設定されていません</h3>
                            <p class="text-gray-400">管理画面から特定商取引法表記の設定を行ってください。</p>
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