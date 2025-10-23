<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('おすすめ商品') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- パンくずリスト -->
            @php
                $breadcrumbItems = [
                    ['title' => 'ホーム', 'url' => route('welcome'), 'icon' => true],
                    ['title' => 'おすすめ商品', 'current' => true]
                ];
            @endphp
            <x-breadcrumb :items="$breadcrumbItems" />

            <!-- おすすめ商品ヘッダー -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">おすすめ商品</h1>
                    <p class="text-lg text-gray-600">厳選された特におすすめの商品をご紹介します。</p>
                </div>
            </div>

            <!-- 商品一覧 -->
            <div class="mb-6">
                <p class="text-gray-600">{{ $featuredProducts->total() }}件のおすすめ商品</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @forelse($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="h-48 bg-gray-100 overflow-hidden">
                                @if($product->mainImage && $product->mainImage->image_path)
                                    <img src="{{ Storage::url($product->mainImage->image_path) }}" 
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" 
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                                <div class="flex justify-between items-center">
                                    <div>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="line-through text-gray-400 text-sm">¥{{ number_format($product->price) }}</span>
                                            <span class="text-red-600 font-bold">¥{{ number_format($product->sale_price) }}</span>
                                        @else
                                            <span class="font-bold">¥{{ number_format($product->price) }}</span>
                                        @endif
                                    </div>
                                    @if($product->stock > 0)
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">在庫あり</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">在庫なし</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">おすすめ商品はありません</h3>
                        <p class="mt-1 text-sm text-gray-500">現在おすすめの商品は登録されていません。</p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{ $featuredProducts->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 