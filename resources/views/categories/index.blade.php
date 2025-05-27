@php
    use Illuminate\Support\Facades\Storage;
@endphp

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
                        <!-- カテゴリー画像 -->
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            @if($category->image_path)
                                <img src="{{ Storage::url($category->image_path) }}" 
                                    alt="{{ $category->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <svg class="h-16 w-16 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
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
                                                @if($child->image_path)
                                                    <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                                                        <img src="{{ Storage::url($child->image_path) }}" 
                                                            alt="{{ $child->name }}" class="w-full h-full object-cover">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 w-12 h-12 mr-3 bg-gray-200 rounded flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
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