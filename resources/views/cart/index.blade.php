<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ショッピングカート') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- パンくずリスト -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            ホーム
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">ショッピングカート</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- カート内容 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">ショッピングカート</h1>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($cart->items->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">商品名</th>
                                        <th class="py-3 px-6 text-center">単価</th>
                                        <th class="py-3 px-6 text-center">数量</th>
                                        <th class="py-3 px-6 text-right">小計</th>
                                        <th class="py-3 px-6 text-center">操作</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm font-light">
                                    @foreach($cart->items as $item)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="py-4 px-6 text-left">
                                                <div class="flex items-center">
                                                    <div class="mr-3 w-16 h-16 flex-shrink-0">
                                                        @if($item->product->mainImage && $item->product->mainImage->image_path)
                                                            <img src="{{ Storage::url($item->product->mainImage->image_path) }}" 
                                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" 
                                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                                            {{ $item->product->name }}
                                                        </a>
                                                        @if(!$item->product->isInStock())
                                                            <p class="text-red-600 text-xs mt-1">この商品は在庫切れです</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                ¥{{ number_format($item->price) }}
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center justify-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" 
                                                        class="w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="py-4 px-6 text-right font-medium">
                                                ¥{{ number_format($item->subtotal) }}
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" 
                                                            onclick="return confirm('本当に削除しますか？');">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- カート合計 -->
                        <div class="mt-8 flex justify-between">
                            <div>
                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                                            onclick="return confirm('カートを空にしますか？');">
                                        カートを空にする
                                    </button>
                                </form>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 mb-2">商品数: {{ $cart->total_quantity }}点</p>
                                <p class="text-gray-600 mb-4">合計金額: <span class="text-2xl font-bold text-gray-900">¥{{ number_format($cart->total) }}</span></p>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                    お買い物を続ける
                                </a>
                                <a href="{{ route('checkout.address') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    レジに進む
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="h-24 w-24 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-2xl font-medium text-gray-900 mb-4">カートは空です</h3>
                            <p class="text-gray-600 mb-8">カートに商品が入っていません。素敵な商品を見つけてください。</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                商品一覧を見る
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 