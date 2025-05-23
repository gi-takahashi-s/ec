@extends('layouts.front')

@section('title', 'メール認証 - ECサイト')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">メール認証</h2>

    <div class="mb-6 text-sm text-gray-600">
        ご登録ありがとうございます。ご利用を開始する前に、先ほどお送りしたメールアドレス認証のリンクをクリックして、メールアドレスの確認をお願いします。
        メールが届いていない場合は、再送信をリクエストすることができます。
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            新しい認証リンクをご登録いただいたメールアドレスに送信しました。
        </div>
    @endif

    <div class="flex items-center justify-between mt-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                認証メールを再送信
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                ログアウト
            </button>
        </form>
    </div>
</div>
@endsection
