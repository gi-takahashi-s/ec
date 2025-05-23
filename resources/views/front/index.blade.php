@extends('layouts.front')

@section('title', 'ECサイト - ホーム')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">ようこそ、ECサイトへ</h2>
        
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">おすすめ商品</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="bg-gray-200 h-40 rounded-md mb-3"></div>
                        <h4 class="text-lg font-medium text-gray-800">ダミー商品 {{ $i }}</h4>
                        <p class="text-gray-600 text-sm mb-2">この商品はダミーです。実際の商品ではありません。</p>
                        <p class="text-blue-600 font-bold">¥{{ rand(1000, 10000) }}</p>
                    </div>
                @endfor
            </div>
        </div>
        
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">新着情報</h3>
            <ul class="space-y-2">
                @for ($i = 1; $i <= 3; $i++)
                    <li class="border-b pb-2">
                        <span class="text-sm text-gray-500">{{ date('Y-m-d', strtotime("-$i day")) }}</span>
                        <p class="text-gray-700">ダミーのお知らせ内容 {{ $i }}</p>
                    </li>
                @endfor
            </ul>
        </div>
        
        <div>
            <a href="{{ route('front.sub') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors inline-block">
                サブページへ
            </a>
        </div>
    </div>
@endsection 