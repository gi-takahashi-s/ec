<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">新着商品</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    <div class="h-48 bg-gray-100 overflow-hidden">
                        <img src="{{ $product->image ? asset($product->image) : asset('images/no-image.png') }}" 
                            alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
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
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('products.index') }}" class="inline-block text-indigo-600 font-semibold hover:text-indigo-800 hover:underline">
            すべての商品を見る →
        </a>
    </div>
</div> 