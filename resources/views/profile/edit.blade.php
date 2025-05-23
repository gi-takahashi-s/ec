@extends('layouts.front')

@section('title', 'プロフィール編集 - ECサイト')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">プロフィール編集</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">アカウントメニュー</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">マイページ</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline font-bold">プロフィール編集</a></li>
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
                <h3 class="text-lg font-semibold text-gray-700 mb-4">プロフィール情報</h3>
                
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">パスワード変更</h3>
                
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">アカウント削除</h3>
                
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
