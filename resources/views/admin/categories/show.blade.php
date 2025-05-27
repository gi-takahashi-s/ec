@extends('layouts.admin')

@section('header', 'カテゴリー詳細')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">カテゴリー詳細: {{ $category->name }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                編集
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('このカテゴリーを削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    削除
                </button>
            </form>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">基本情報</h3>
                
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->id }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">カテゴリー名</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->name }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">スラグ</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->slug }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">説明</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            {{ $category->description ?: '説明はありません' }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">表示順</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->sort_order }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">表示状態</h4>
                        <p class="mt-1">
                            @if($category->is_visible)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">表示</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">非表示</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">親カテゴリー</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $category->parent->name }}
                                </a>
                            @else
                                親カテゴリーなし
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">作成日時</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->created_at->format('Y/m/d H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">更新日時</h4>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $category->updated_at->format('Y/m/d H:i:s') }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">画像</h3>
                
                <div class="space-y-4">
                    <div>
                        @if($category->image_path)
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="h-48 w-auto object-cover rounded border border-gray-200 dark:border-gray-700">
                        @else
                            <div class="h-48 w-48 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8 mb-4">子カテゴリー</h3>
                
                <div class="space-y-4">
                    @if($category->children->count() > 0)
                        <ul class="space-y-2">
                            @foreach($category->children as $childCategory)
                                <li>
                                    <a href="{{ route('admin.categories.show', $childCategory) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $childCategory->name }}
                                    </a>
                                    @if(!$childCategory->is_visible)
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">非表示</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">子カテゴリーはありません</p>
                    @endif
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8 mb-4">商品数</h3>
                
                <div class="space-y-4">
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $category->products->count() }}
                    </p>
                    
                    @if($category->products->count() > 0)
                        <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-25 transition">
                            商品一覧を表示
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-600 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            一覧に戻る
        </a>
    </div>
</div>
@endsection 