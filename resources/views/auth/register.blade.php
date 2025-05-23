@extends('layouts.front')

@section('title', '会員登録 - ECサイト')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- 名前 -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">お名前</label>
            <input id="name" type="text" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">メールアドレス</label>
            <input id="email" type="email" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">パスワード</label>
            <input id="password" type="password" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
            @error('password')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- パスワード（確認） -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">パスワード（確認）</label>
            <input id="password_confirmation" type="password" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                登録する
            </button>
            
            <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="{{ route('login') }}">
                既に会員の方はこちら
            </a>
        </div>
    </form>
</div>
@endsection
