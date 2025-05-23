<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← 商品一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- パンくずリスト -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            ホーム
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('products.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">商品一覧</a>
                        </div>
                    </li>
                    @if($product->category)
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('categories.show', $product->category->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">{{ $product->category->name }}</a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- 商品詳細 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- 商品画像 -->
                    <div>
                        <div class="bg-gray-100 rounded-lg overflow-hidden h-80">
                            <img src="{{ $product->image ? asset($product->image) : asset('images/no-image.png') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover">
                        </div>
                        <!-- 商品サブ画像（もしあれば） -->
                        @if($product->images && $product->images->count() > 0)
                        <div class="grid grid-cols-4 gap-2 mt-4">
                            @foreach($product->images as $image)
                            <div class="bg-gray-100 rounded-lg overflow-hidden h-20">
                                <img src="{{ asset($image->path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover cursor-pointer hover:opacity-80 transition-opacity">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- 商品情報 -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        @if($product->category)
                        <a href="{{ route('categories.show', $product->category->slug) }}" 
                            class="inline-block bg-gray-100 text-gray-800 text-sm font-medium rounded-full px-3 py-1 mb-4 hover:bg-gray-200 transition-colors">
                            {{ $product->category->name }}
                        </a>
                        @endif

                        <!-- 価格表示 -->
                        <div class="mb-6">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm text-gray-500">通常価格:</span>
                                    <span class="text-lg text-gray-500 line-through">¥{{ number_format($product->price) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-red-600 font-semibold">セール価格:</span>
                                    <span class="text-3xl text-red-600 font-bold">¥{{ number_format($product->sale_price) }}</span>
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded ml-2">
                                        {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">価格:</span>
                                    <span class="text-3xl text-gray-900 font-bold">¥{{ number_format($product->price) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- 在庫状況 -->
                        <div class="mb-6">
                            @if($product->stock > 0)
                                <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded">在庫あり (残り{{ $product->stock }}点)</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-sm font-semibold px-3 py-1 rounded">在庫切れ</span>
                            @endif
                        </div>

                        <!-- 商品コード -->
                        <div class="mb-6 text-sm text-gray-500">
                            商品コード: <span class="font-mono">{{ $product->sku }}</span>
                        </div>

                        <!-- カートに追加ボタン -->
                        <div class="mb-8">
                            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="w-24">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">数量</label>
                                    <input type="number" name="quantity" id="quantity" min="1" max="{{ $product->stock }}" value="1" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                </div>
                                <button type="submit" 
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    カートに追加
                                </button>
                            </form>
                        </div>

                        <!-- 商品説明 -->
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-2">商品説明</h2>
                            <div class="text-gray-700 whitespace-pre-line">{{ $product->description }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 商品詳細タブ -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-12" x-data="{ activeTab: 'features' }">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'features'" 
                            :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'features', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'features' }" 
                            class="py-4 px-6 border-b-2 font-medium text-sm">
                            商品の特徴
                        </button>
                        <button @click="activeTab = 'specifications'" 
                            :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'specifications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'specifications' }" 
                            class="py-4 px-6 border-b-2 font-medium text-sm">
                            仕様
                        </button>
                    </nav>
                </div>
                <div class="p-6">
                    <div x-show="activeTab === 'features'" class="whitespace-pre-line">{{ $product->features }}</div>
                    <div x-show="activeTab === 'specifications'" class="whitespace-pre-line">{{ $product->specifications }}</div>
                </div>
            </div>

            <!-- 関連商品 -->
            @if($relatedProducts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">関連商品</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                                <div class="h-48 bg-gray-100 overflow-hidden">
                                    <img src="{{ $relatedProduct->image ? asset($relatedProduct->image) : asset('images/no-image.png') }}" 
                                        alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $relatedProduct->name }}</h3>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                                <span class="line-through text-gray-400 text-sm">¥{{ number_format($relatedProduct->price) }}</span>
                                                <span class="text-red-600 font-bold">¥{{ number_format($relatedProduct->sale_price) }}</span>
                                            @else
                                                <span class="font-bold">¥{{ number_format($relatedProduct->price) }}</span>
                                            @endif
                                        </div>
                                        @if($relatedProduct->stock > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">在庫あり</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">在庫なし</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout> 