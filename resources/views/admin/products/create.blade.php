@extends('layouts.admin')

@section('header', '商品登録')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- 基本情報 -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">基本情報</h3>
                <div class="mt-4 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品名 <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @foreach($category->children as $child)
                                        <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                            　 {{ $child->name }}
                                        </option>
                                    @endforeach
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
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
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
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="features" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品の特徴</label>
                        <div class="mt-1">
                            <textarea id="features" name="features" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('features') }}</textarea>
                        </div>
                        @error('features')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="specifications" class="block text-sm font-medium text-gray-700 dark:text-gray-300">商品の仕様</label>
                        <div class="mt-1">
                            <textarea id="specifications" name="specifications" rows="3"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">{{ old('specifications') }}</textarea>
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
                            <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="1" required
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
                            <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" min="0" step="1"
                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                        </div>
                        @error('sale_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">在庫数 <span class="text-red-600">*</span></label>
                        <div class="mt-1">
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required
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
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    画像をドラッグ&ドロップで登録できます。最初の画像がメイン画像になります。画像をドラッグして並び替えることができます。
                </p>
                
                <!-- 画像プレビューエリア -->
                <div id="image-preview-container" class="mt-4">
                    <div id="image-list" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 mb-4"></div>
                </div>
                
                <!-- ドロップボックスコンテナ -->
                <div id="dropbox-container" class="space-y-4">
                    <!-- 最初のドロップボックス -->
                    <div class="image-dropbox border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors duration-200" data-index="0">
                        <div class="dropbox-content">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-indigo-600 dark:text-indigo-400">クリックして画像を選択</span>
                                    またはドラッグ&ドロップ
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG, GIF (最大2MB)</p>
                            </div>
                        </div>
                        <input type="file" class="hidden" accept="image/*" data-index="0">
                    </div>
                </div>
                
                <!-- 隠しフィールド（フォーム送信用） -->
                <input type="hidden" name="main_image_data" id="main_image_data">
                <input type="hidden" name="additional_images_data" id="additional_images_data">
                
                @error('main_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('additional_images')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('additional_images.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 表示設定 -->
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">表示設定</h3>
                <div class="mt-4 space-y-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_visible" name="is_visible" type="checkbox" value="1" {{ old('is_visible') ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_visible" class="font-medium text-gray-700 dark:text-gray-300">商品を表示する</label>
                            <p class="text-gray-500 dark:text-gray-400">商品をサイトに公開します。チェックを外すと非表示になります。</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}
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
                <a href="{{ route('admin.products.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    キャンセル
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    商品を登録
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let imageFiles = [];
    let imageCounter = 0;
    
    // ドラッグ&ドロップとクリック機能の初期化
    function initializeDropbox(dropbox) {
        const fileInput = dropbox.querySelector('input[type="file"]');
        
        // クリックでファイル選択
        dropbox.addEventListener('click', function() {
            fileInput.click();
        });
        
        // ファイル選択時の処理
        fileInput.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });
        
        // ドラッグオーバー時のスタイル
        dropbox.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropbox.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
        });
        
        // ドラッグリーブ時のスタイル
        dropbox.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropbox.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
        });
        
        // ドロップ時の処理
        dropbox.addEventListener('drop', function(e) {
            e.preventDefault();
            dropbox.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900');
            handleFiles(e.dataTransfer.files);
        });
    }
    
    // ファイル処理
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) {
                addImageToList(file);
            }
        });
    }
    
    // 画像をリストに追加
    function addImageToList(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imageData = {
                id: 'img_' + imageCounter++,
                file: file,
                dataUrl: e.target.result,
                isMain: imageFiles.length === 0
            };
            
            imageFiles.push(imageData);
            renderImageList();
            updateDropboxes();
            updateHiddenFields();
        };
        reader.readAsDataURL(file);
    }
    
    // 画像リストの描画
    function renderImageList() {
        const imageList = document.getElementById('image-list');
        imageList.innerHTML = '';
        
        imageFiles.forEach((imageData, index) => {
            const imageItem = document.createElement('div');
            imageItem.className = 'relative group cursor-move';
            imageItem.draggable = true;
            imageItem.dataset.imageId = imageData.id;
            
            imageItem.innerHTML = `
                <div class="aspect-w-1 aspect-h-1 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                    <img src="${imageData.dataUrl}" alt="商品画像" class="w-full h-full object-cover">
                </div>
                ${imageData.isMain ? 
                    '<div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">メイン</div>' : 
                    ''
                }
                <button type="button" class="absolute top-2 right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" onclick="removeImage('${imageData.id}')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            // ドラッグイベント
            imageItem.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', imageData.id);
                imageItem.classList.add('opacity-50');
            });
            
            imageItem.addEventListener('dragend', function(e) {
                imageItem.classList.remove('opacity-50');
            });
            
            imageItem.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            
            imageItem.addEventListener('drop', function(e) {
                e.preventDefault();
                const draggedId = e.dataTransfer.getData('text/plain');
                const droppedId = imageData.id;
                
                if (draggedId !== droppedId) {
                    reorderImages(draggedId, droppedId);
                }
            });
            
            imageList.appendChild(imageItem);
        });
    }
    
    // 画像の並び替え
    function reorderImages(draggedId, droppedId) {
        const draggedIndex = imageFiles.findIndex(img => img.id === draggedId);
        const droppedIndex = imageFiles.findIndex(img => img.id === droppedId);
        
        if (draggedIndex !== -1 && droppedIndex !== -1) {
            const draggedImage = imageFiles.splice(draggedIndex, 1)[0];
            imageFiles.splice(droppedIndex, 0, draggedImage);
            
            // メイン画像の更新
            imageFiles.forEach((img, index) => {
                img.isMain = index === 0;
            });
            
            renderImageList();
            updateHiddenFields();
        }
    }
    
    // 画像削除
    window.removeImage = function(imageId) {
        imageFiles = imageFiles.filter(img => img.id !== imageId);
        
        // メイン画像の再設定
        if (imageFiles.length > 0) {
            imageFiles[0].isMain = true;
        }
        
        renderImageList();
        updateDropboxes();
        updateHiddenFields();
    };
    
    // ドロップボックスの更新
    function updateDropboxes() {
        const container = document.getElementById('dropbox-container');
        const dropboxes = container.querySelectorAll('.image-dropbox');
        
        // 画像が登録されている場合は次のドロップボックスを表示
        if (imageFiles.length > 0 && dropboxes.length === 1) {
            const newDropbox = createDropbox(1);
            container.appendChild(newDropbox);
        }
        
        // 画像がない場合は最初のドロップボックスのみ表示
        if (imageFiles.length === 0 && dropboxes.length > 1) {
            for (let i = 1; i < dropboxes.length; i++) {
                dropboxes[i].remove();
            }
        }
    }
    
    // 新しいドロップボックスの作成
    function createDropbox(index) {
        const dropbox = document.createElement('div');
        dropbox.className = 'image-dropbox border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors duration-200';
        dropbox.dataset.index = index;
        
        dropbox.innerHTML = `
            <div class="dropbox-content">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-indigo-600 dark:text-indigo-400">クリックして画像を選択</span>
                        またはドラッグ&ドロップ
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">PNG, JPG, GIF (最大2MB)</p>
                </div>
            </div>
            <input type="file" class="hidden" accept="image/*" data-index="${index}">
        `;
        
        initializeDropbox(dropbox);
        return dropbox;
    }
    
    // 隠しフィールドの更新
    function updateHiddenFields() {
        const mainImageData = document.getElementById('main_image_data');
        const additionalImagesData = document.getElementById('additional_images_data');
        
        if (imageFiles.length > 0) {
            // メイン画像
            mainImageData.value = imageFiles[0].dataUrl;
            
            // 追加画像
            const additionalImages = imageFiles.slice(1).map(img => img.dataUrl);
            additionalImagesData.value = JSON.stringify(additionalImages);
        } else {
            mainImageData.value = '';
            additionalImagesData.value = '';
        }
    }
    
    // 初期化
    const initialDropbox = document.querySelector('.image-dropbox');
    if (initialDropbox) {
        initializeDropbox(initialDropbox);
    }
});
</script>
@endsection

@section('styles')
<style>
.aspect-w-1 {
    position: relative;
    padding-bottom: 100%;
}

.aspect-h-1 {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
</style>
@endsection 