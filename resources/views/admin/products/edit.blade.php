@extends('layouts.admin')

@section('header', '商品編集')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- 基本情報 -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">基本情報</h3>
                <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品名 <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <select id="category_id" name="category_id" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                                <option value="">カテゴリーを選択してください</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-3">
                        <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('sku')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品説明 <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('description', $product->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="features" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品の特徴</label>
                        <div class="mt-1">
                            <textarea id="features" name="features" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('features', $product->features) }}</textarea>
                        </div>
                        @error('features')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="specifications" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品の仕様</label>
                        <div class="mt-1">
                            <textarea id="specifications" name="specifications" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('specifications', $product->specifications) }}</textarea>
                        </div>
                        @error('specifications')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- 価格と在庫 -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">価格と在庫</h3>
                <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">販売価格 <span class="text-red-600">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">¥</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" min="0" required
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">セール価格</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">¥</span>
                            </div>
                            <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0"
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('sale_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">在庫数 <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('stock')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- 商品画像 -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">商品画像</h3>
                
                <!-- 現在の画像 -->
                @if($product->images && $product->images->count() > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">現在の画像</h4>
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <div class="flex space-x-2">
                                            @if(!$image->is_main)
                                                <div class="flex items-center">
                                                    <input type="checkbox" id="delete_images_{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                    <label for="delete_images_{{ $image->id }}" class="ml-2 text-sm text-white">削除</label>
                                                </div>
                                            @else
                                                <span class="text-xs text-white bg-indigo-600 px-2 py-1 rounded">メイン画像</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">※削除したい画像にチェックを入れてください。メイン画像は削除できません。</p>
                    </div>
                @endif
                
                <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="main_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">新しいメイン画像</label>
                        <div class="mt-1 flex items-center">
                            <div class="flex-shrink-0 h-32 w-32 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                                <img id="main_image_preview" src="#" alt="メイン画像プレビュー" class="h-full w-full object-cover hidden">
                                <div id="main_image_placeholder" class="h-full w-full flex items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="relative bg-white dark:bg-gray-700 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">ファイルを選択</span>
                                    <input id="main_image" name="main_image" type="file" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">現在のメイン画像を置き換える場合のみアップロードしてください</p>
                            </div>
                        </div>
                        @error('main_image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="additional_images" class="block text-sm font-medium text-gray-700 dark:text-gray-300">追加画像（複数選択可）</label>
                        <div class="mt-1">
                            <div class="relative bg-white dark:bg-gray-700 py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">ファイルを選択</span>
                                <input id="additional_images" name="additional_images[]" type="file" multiple accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div id="additional_images_preview" class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4"></div>
                        @error('additional_images')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('additional_images.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- 表示設定 -->
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">表示設定</h3>
                <div class="mt-4 space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_visible" name="is_visible" type="checkbox" value="1" {{ old('is_visible', $product->is_visible) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_visible" class="font-medium text-gray-700 dark:text-gray-300">商品を表示する</label>
                            <p class="text-gray-500 dark:text-gray-400">商品をサイトに公開します。チェックを外すと非表示になります。</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_featured" class="font-medium text-gray-700 dark:text-gray-300">おすすめ商品にする</label>
                            <p class="text-gray-500 dark:text-gray-400">商品をおすすめとして特集します。</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 送信ボタン -->
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right">
                <a href="{{ route('admin.products.show', $product) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    キャンセル
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    商品を更新
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // メイン画像プレビュー
    document.getElementById('main_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('main_image_preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('main_image_placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // 追加画像プレビュー
    document.getElementById('additional_images').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('additional_images_preview');
        previewContainer.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'relative h-24 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'h-full w-full object-cover';
                
                previewItem.appendChild(img);
                previewContainer.appendChild(previewItem);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection 