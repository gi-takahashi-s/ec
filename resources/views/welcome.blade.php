<x-app-layout>
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ヒーローセクション -->
            <div class="relative rounded-xl shadow-lg overflow-hidden mb-8">
                <!-- 背景画像 -->
                <!-- グラデーション背景 -->
                <!-- <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div> -->
                
                <!-- 実際の画像ファイルを使用する場合は以下を有効にして上記のグラデーションをコメントアウト -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/hero.jpg') }}'); background-size: cover; background-position: center;"></div>
                
                <!-- オーバーレイ -->
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                
                <!-- パターン背景 -->
                <!-- <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzR2LTRoLTJ2NGgtNHYyaDR2NGgydi00aDR2LTJoLTR6bTAtMzBWMGgtMnY0aC00djJoNHY0aDJWNmg0VjRoLTR6TTYgMzR2LTRINHY0SDB2Mmg0djRoMnYtNGg0di0ySDZ6TTYgNFYwSDR2NEgwdjJoNHY0aDJWNmg0VjRINnoiLz48L2c+PC9nPjwvc3ZnPg=='); background-size: 60px 60px;"></div> -->
                
                <!-- コンテンツ -->
                <div class="relative px-6 py-12 md:px-12 md:py-16 text-center text-white">
                    @php
                        $shopDisplay = \App\Services\ShopSettingService::getShopLogoOrName();
                    @endphp
                    @if($shopDisplay['type'] === 'logo')
                        <!-- <img src="{{ $shopDisplay['value'] }}" alt="{{ $shopDisplay['alt'] }}" class="h-16 w-auto mx-auto mb-4"> -->
                        <h1 class="text-3xl md:text-5xl font-bold mb-4 drop-shadow-lg">{{ $shopDisplay['alt'] }}へようこそ</h1>
                    @else
                        <h1 class="text-3xl md:text-5xl font-bold mb-4 drop-shadow-lg">{{ $shopDisplay['value'] }}へようこそ</h1>
                    @endif
                    <p class="text-lg md:text-xl mb-6 drop-shadow">厳選された商品を多数取り揃えております</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-white text-indigo-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-gray-100 hover:shadow-xl transition duration-300 transform hover:scale-105">
                        商品一覧を見る
                    </a>
                </div>
            </div>

            <!-- カテゴリーセクション -->
            @include('partials.home-categories')

            <!-- おすすめ商品セクション -->
            @include('partials.home-featured-products')

            <!-- 新着商品セクション -->
            @include('partials.home-new-products')
        </div>
    </div>
</x-app-layout>
