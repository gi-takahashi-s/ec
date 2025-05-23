@extends('layouts.front')

@section('title', 'ログイン - ECサイト')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">ログイン</h2>

    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- メールアドレス -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">メールアドレス</label>
            <input id="email" type="email" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">パスワード</label>
            <input id="password" type="password" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- ログイン情報を記憶 -->
        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">ログイン情報を記憶する</span>
            </label>
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ログイン
            </button>
            
            @if (Route::has('password.request'))
                <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                    パスワードをお忘れですか？
                </a>
            @endif
        </div>
        
        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">アカウントをお持ちでない方は <a href="{{ route('register') }}" class="text-blue-600 hover:underline">こちら</a> から会員登録</p>
        </div>
    </form>
</div>
@endsection
