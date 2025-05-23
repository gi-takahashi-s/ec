@extends('layouts.front')

@section('title', 'マイページ - ECサイト')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">マイページ</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">アカウントメニュー</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline font-bold">マイページ</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">プロフィール編集</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:underline">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ Auth::user()->name }}様、ようこそ</h3>
                <p class="text-gray-600 mb-4">
                    最終ログイン日時: {{ Auth::user()->updated_at->format('Y年m月d日 H:i') }}
                </p>
                
                <div class="flex space-x-4 mt-4">
                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        注文履歴を見る
                    </a>
                    <a href="{{ route('profile.edit') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        会員情報を編集する
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">最近の注文</h3>
                <p class="text-gray-600">
                    注文履歴はまだありません。
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
