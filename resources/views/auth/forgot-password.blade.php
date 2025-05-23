@extends('layouts.front')

@section('title', 'パスワードをお忘れの方 - ECサイト')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">パスワードの再設定</h2>

    <div class="mb-6 text-sm text-gray-600">
        メールアドレスを入力してください。パスワードリセット用のリンクをメールで送信します。
    </div>

    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- メールアドレス -->
        <div class="mb-6">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">メールアドレス</label>
            <input id="email" type="email" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                パスワードリセットリンクを送信
            </button>
            
            <a class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800" href="{{ route('login') }}">
                ログインに戻る
            </a>
        </div>
    </form>
</div>
@endsection
