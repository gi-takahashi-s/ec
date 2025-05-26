@extends('layouts.admin')

@section('header', 'ユーザー編集')

@section('content')
    <!-- アクションボタン -->
    <div class="mb-6 flex">
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                詳細に戻る
            </a>
        </div>
    </div>

    <!-- ユーザー編集フォーム -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                ユーザー情報編集
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                ID: {{ $user->id }} - {{ $user->email }}
            </p>
        </div>
        
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- 名前 -->
                <div class="sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">名前 <span class="text-red-600">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- メールアドレス -->
                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">メールアドレス <span class="text-red-600">*</span></label>
                    <div class="mt-1">
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 管理者権限 -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_admin" name="is_admin" type="checkbox" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_admin" class="font-medium text-gray-700 dark:text-gray-300">管理者権限</label>
                            <p class="text-gray-500 dark:text-gray-400">このユーザーに管理者権限を付与します。</p>
                        </div>
                    </div>
                    @error('is_admin')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- パスワード -->
                <div class="sm:col-span-3">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">新しいパスワード</label>
                    <div class="mt-1">
                        <input type="password" name="password" id="password" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">変更しない場合は空欄にしてください</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- パスワード確認 -->
                <div class="sm:col-span-3">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">パスワード（確認）</label>
                    <div class="mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                    </div>
                </div>
            </div>
            
            <!-- 送信ボタン -->
            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
                    キャンセル
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    更新する
                </button>
            </div>
        </form>
    </div>
@endsection 