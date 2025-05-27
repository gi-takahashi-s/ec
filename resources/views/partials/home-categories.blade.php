@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="mb-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">商品カテゴリー</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" class="block group">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 group-hover:shadow-md transition-shadow duration-300">
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        <img src="{{ $category->image_path ? Storage::url($category->image_path) : asset('images/no-category-image.png') }}" 
                            alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-300">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="text-center mt-8">
        <a href="{{ route('categories.index') }}" class="inline-block text-indigo-600 font-semibold hover:text-indigo-800 hover:underline">
            すべてのカテゴリーを見る →
        </a>
    </div>
</div> 