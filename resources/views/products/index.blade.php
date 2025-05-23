<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- フィルターと検索 -->
            <div class="mb-8 bg-white p-4 rounded-lg shadow">
                <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="category" class="block text-sm font-medium text-gray-700">カテゴリー</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">すべてのカテゴリー</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="sort" class="block text-sm font-medium text-gray-700">並び替え</label>
                        <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>新着順</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格: 安い順</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格: 高い順</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700">キーワード検索</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                            placeholder="商品名や説明で検索">
                    </div>
                    <div class="self-end">
                        <button type="submit" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            検索
                        </button>
                    </div>
                </form>
            </div>

            <!-- 検索結果 -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    @if(request('search'))
                        「{{ request('search') }}」の検索結果 - {{ $products->total() }}件
                    @else
                        全{{ $products->total() }}件の商品
                    @endif
                </h3>
            </div>

            <!-- 商品一覧 -->
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">該当する商品がありません</h3>
                        <p class="mt-1 text-sm text-gray-500">検索条件を変更してお試しください。</p>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 