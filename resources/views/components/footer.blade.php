<footer class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- ショップ情報 -->
            <div>
                <h3 class="text-lg font-semibold mb-4">ショップ情報</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="{{ route('welcome') }}" class="hover:text-white transition-colors">ホーム</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">商品一覧</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-white transition-colors">カテゴリー</a></li>
                    <li><a href="{{ route('products.featured') }}" class="hover:text-white transition-colors">おすすめ商品</a></li>
                </ul>
            </div>

            <!-- 法的情報 -->
            <div>
                <h3 class="text-lg font-semibold mb-4">　</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="{{ route('mypage') }}" class="hover:text-white transition-colors">マイページ</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-white transition-colors">注文履歴</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">プライバシーポリシー</a></li>
                    <li><a href="{{ route('legal') }}" class="hover:text-white transition-colors">特定商取引法表記</a></li>
                </ul>
            </div>

            <!-- 空のスペース -->
            <div>
                <h3 class="text-lg font-semibold mb-4">ご利用について</h3>
                <h4 class="text-md font-semibold mb-3">お支払い方法</h4>
                <div class="flex flex-wrap gap-3 mb-6">
                    @php
                    $paymentSettings = \App\Services\ShopSettingService::getPaymentSettings();
                    @endphp
                    @if($paymentSettings['stripe_enabled'])
                    <div class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-medium">クレジットカード</div>
                    @endif
                    @if($paymentSettings['bank_transfer_enabled'])
                    <div class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-medium">銀行振込</div>
                    @endif
                    <div class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-medium">代金引換</div>
                </div>
                <h4 class="text-md font-semibold mb-3">配送について</h4>
                <div class="text-sm text-gray-300">
                    @php
                    $shippingSettings = \App\Services\ShopSettingService::getShippingSettings();
                    @endphp
                    <p>配送料金: {{ number_format($shippingSettings['shipping_fee']) }}円（税込）</p>
                    @if($shippingSettings['free_shipping_threshold'] > 0)
                    <p>{{ number_format($shippingSettings['free_shipping_threshold']) }}円以上のお買い上げで送料無料</p>
                    @endif
                    <p>配送日数: {{ $shippingSettings['shipping_days'] }}日程度</p>
                    @if($shippingSettings['shipping_company'])
                    <p>配送業者: {{ $shippingSettings['shipping_company'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- SNS・お問い合わせ -->
        <div class="border-t border-gray-700 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- SNSリンク -->
                <div class="mb-4 md:mb-0">
                    <h4 class="text-md font-semibold mb-3">フォローする</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors" aria-label="Twitter">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors" aria-label="Instagram">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.297-3.323C5.902 8.198 7.053 7.708 8.35 7.708s2.448.49 3.323 1.297c.897.875 1.387 2.026 1.387 3.323s-.49 2.448-1.297 3.323c-.875.897-2.026 1.387-3.323 1.387zm7.718 0c-1.297 0-2.448-.49-3.323-1.297-.897-.875-1.387-2.026-1.387-3.323s.49-2.448 1.297-3.323c.875-.897 2.026-1.387 3.323-1.387s2.448.49 3.323 1.297c.897.875 1.387 2.026 1.387 3.323s-.49 2.448-1.297 3.323c-.875.897-2.026 1.387-3.323 1.387z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors" aria-label="LINE">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- お問い合わせ情報 -->
                <div class="text-center md:text-right">
                    <h4 class="text-md font-semibold mb-2">お問い合わせ</h4>
                    @php
                    $basicInfo = \App\Services\ShopSettingService::getBasicInfo();
                    @endphp
                    @if($basicInfo['business_hours'])
                    <p class="text-sm text-gray-300">{{ $basicInfo['business_hours'] }}</p>
                    @endif
                    @if($basicInfo['phone_number'])
                    <p class="text-sm text-gray-300">TEL: {{ $basicInfo['phone_number'] }}</p>
                    @endif
                    @if($basicInfo['shop_name'])
                    <p class="text-sm text-gray-300">{{ $basicInfo['shop_name'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- コピーライト -->
        <div class="border-t border-gray-700 mt-8 pt-6 text-center">
            @php
            $basicInfo = \App\Services\ShopSettingService::getBasicInfo();
            $shopName = $basicInfo['shop_name'] ?: 'ECサイト';
            @endphp
            <p class="text-sm text-gray-400">&copy; {{ date('Y') }} {{ $shopName }} All Rights Reserved.</p>
        </div>
    </div>
</footer>