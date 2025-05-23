<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>
            <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← カテゴリー一覧に戻る
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
                            <a href="{{ route('categories.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">カテゴリー一覧</a>
                        </div>
                    </li>
                    @if($category->parent)
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('categories.show', $category->parent->slug) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">{{ $category->parent->name }}</a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- カテゴリーヘッダー -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-lg text-gray-600 mb-4">{{ $category->description }}</p>
                    @endif

                    <!-- サブカテゴリーがある場合 -->
                    @if($category->children && $category->children->count() > 0)
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">サブカテゴリー</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($category->children as $child)
                                    <a href="{{ route('categories.show', $child->slug) }}" 
                                        class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $child->name }}</h3>
                                            @if($child->description)
                                                <p class="text-sm text-gray-600 line-clamp-1">{{ $child->description }}</p>
                                            @endif
                                        </div>
                                        <svg class="ml-auto h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 商品一覧 -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $category->name }}の商品一覧</h2>
                <p class="text-gray-600">{{ $products->total() }}件の商品</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="h-48 bg-gray-100 overflow-hidden">
                                <img src="{{ $product->image ? asset($product->image) : asset('images/no-image.png') }}" 
                                    alt="{{ $product->name }}" class="w-full h-full object-cover">
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">商品がありません</h3>
                        <p class="mt-1 text-sm text-gray-500">このカテゴリーにはまだ商品が登録されていません。</p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 