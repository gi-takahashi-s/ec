@extends('layouts.admin')

@section('header', 'カテゴリー編集')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">カテゴリー編集: {{ $category->name }}</h2>
    </div>
    
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 名前 -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    onkeyup="generateSlug(this.value)">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- スラグ -->
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">スラグ（URL用）</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 説明 -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">説明</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 親カテゴリー -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">親カテゴリー</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">親カテゴリーなし</option>
                    @foreach($categories as $parentCategory)
                        <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                            {{ $parentCategory->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 表示順 -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">表示順</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">数字が小さいほど前に表示されます</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 画像 -->
            <div class="md:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">カテゴリー画像</label>
                
                @if($category->image_path)
                <div class="mt-1 mb-3">
                    <div class="relative">
                        <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="h-32 w-32 object-cover rounded border border-gray-200 dark:border-gray-700">
                        <div class="absolute top-0 right-0 -mt-2 -mr-2">
                            <div class="text-xs text-gray-500 dark:text-gray-400">現在の画像</div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="mt-1 flex items-center">
                    <div id="image-preview" class="hidden mb-3">
                        <img src="#" alt="プレビュー" class="h-32 w-32 object-cover rounded border border-gray-200 dark:border-gray-700">
                    </div>
                </div>
                <div class="mt-1 flex items-center">
                    <input type="file" name="image" id="image" accept="image/*"
                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-200 hover:file:bg-blue-100 dark:hover:file:bg-blue-800">
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">新しい画像を選択すると現在の画像は置き換えられます</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 表示設定 -->
            <div class="md:col-span-2">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}
                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_visible" class="font-medium text-gray-700 dark:text-gray-300">表示する</label>
                        <p class="text-gray-500 dark:text-gray-400">チェックを外すと非表示になります</p>
                    </div>
                </div>
                @error('is_visible')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-end">
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-600 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition mr-3">
                キャンセル
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                更新
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    // スラグ自動生成
    function generateSlug(value) {
        const slug = value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '') // 特殊文字を削除
            .replace(/[\s_-]+/g, '-') // スペースやアンダースコアをハイフンに変換
            .replace(/^-+|-+$/g, ''); // 先頭と末尾のハイフンを削除
        
        document.getElementById('slug').value = slug;
    }
    
    // 画像プレビュー
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.classList.remove('hidden');
                preview.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection 