@extends('layouts.admin')

@section('header', '商品詳細')

@section('content')
    <!-- アクションボタン -->
    <div class="mb-6 flex justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                一覧に戻る
            </a>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                編集
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                    onclick="return confirm('本当にこの商品を削除しますか？この操作は元に戻せません。')">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    削除
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        {{ $product->name }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        SKU: {{ $product->sku }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($product->is_visible)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                            表示中
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            非表示
                        </span>
                    @endif
                    
                    @if($product->is_featured)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                            おすすめ商品
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row">
            <!-- 商品画像 -->
            <div class="w-full md:w-1/3 p-6 border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-700">
                <div class="space-y-4">
                    @if($product->mainImage && $product->mainImage->image_path)
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <img src="{{ Storage::url($product->mainImage->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                        </div>
                    @else
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="h-16 w-16 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    @if($product->images && $product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images->where('is_main', false) as $image)
                                <div class="aspect-w-1 aspect-h-1 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- 商品詳細 -->
            <div class="w-full md:w-2/3 p-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">カテゴリー</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->category->name ?? '未分類' }}</dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">在庫数</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($product->stock <= 0)
                                <span class="text-red-600 dark:text-red-400">在庫切れ ({{ $product->stock }})</span>
                            @elseif($product->stock <= 5)
                                <span class="text-yellow-600 dark:text-yellow-400">残りわずか ({{ $product->stock }})</span>
                            @else
                                <span>{{ $product->stock }}</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">通常価格</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">¥{{ number_format($product->price) }}</dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">セール価格</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if($product->sale_price)
                                <span class="text-red-600 dark:text-red-400">¥{{ number_format($product->sale_price) }}</span>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">設定なし</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">商品説明</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $product->description }}</dd>
                    </div>
                    
                    @if($product->features)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">商品の特徴</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $product->features }}</dd>
                        </div>
                    @endif
                    
                    @if($product->specifications)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">商品の仕様</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">{{ $product->specifications }}</dd>
                        </div>
                    @endif
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">作成日</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">最終更新日</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->updated_at->format('Y/m/d H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection 