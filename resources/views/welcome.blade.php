<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ヒーローセクション -->
            <div class="bg-indigo-600 rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="px-6 py-12 md:px-12 md:py-16 text-center text-white">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">ECショップへようこそ</h1>
                    <p class="text-lg md:text-xl mb-6">厳選された商品を多数取り揃えております</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-white text-indigo-600 font-bold py-3 px-6 rounded-lg shadow hover:bg-gray-100 transition duration-300">
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
