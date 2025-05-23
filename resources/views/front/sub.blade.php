@extends('layouts.front')

@section('title', 'ECサイト - サブページ')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">サブページ</h2>
        
        <div class="mb-8">
            <p class="text-gray-700 mb-4">これはサブページのサンプルコンテンツです。実際のサイトでは、ここに各種情報を掲載します。</p>
            
            <div class="bg-gray-50 p-4 rounded-md mb-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">よくある質問</h3>
                
                <div class="space-y-4">
                    @for ($i = 1; $i <= 3; $i++)
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Q. よくある質問 {{ $i }}？</h4>
                            <p class="text-gray-600">A. これはダミーの回答です。実際のコンテンツではありません。</p>
                        </div>
                    @endfor
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-md mb-6">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">お知らせ</h3>
                
                <div class="space-y-3">
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="bg-white p-3 rounded shadow-sm">
                            <span class="text-sm text-gray-500 block mb-1">{{ date('Y-m-d', strtotime("-$i week")) }}</span>
                            <h4 class="font-medium text-gray-800">重要なお知らせ {{ $i }}</h4>
                            <p class="text-gray-600 text-sm">これはダミーのお知らせです。実際のお知らせではありません。</p>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        
        <div>
            <a href="{{ route('front.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition-colors inline-block">
                ホームに戻る
            </a>
        </div>
    </div>
@endsection 