<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('カテゴリー一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- カテゴリー一覧 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($categories as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $category->name }}</h2>
                            @if($category->description)
                                <p class="text-gray-600 mb-6">{{ $category->description }}</p>
                            @endif
                            
                            <a href="{{ route('categories.show', $category->slug) }}" 
                                class="inline-block bg-indigo-600 text-white font-semibold py-2 px-4 rounded hover:bg-indigo-700 transition-colors">
                                このカテゴリーを見る
                            </a>

                            @if($category->children && $category->children->count() > 0)
                                <div class="mt-8">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4">サブカテゴリー</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($category->children as $child)
                                            <a href="{{ route('categories.show', $child->slug) }}" 
                                                class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                <div>
                                                    <h4 class="font-medium text-gray-900">{{ $child->name }}</h4>
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
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout> 