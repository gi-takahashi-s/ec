@extends('layouts.admin')

@section('header', 'サイト設定')

@section('content')
    <!-- ステータスメッセージ -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- 設定フォーム -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                基本設定
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                サイトの基本的な設定を行います。
            </p>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- サイト名 -->
                <div class="sm:col-span-3">
                    <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">サイト名 <span class="text-red-600">*</span></label>
                    <div class="mt-1">
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                    </div>
                    @error('site_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 通貨 -->
                <div class="sm:col-span-3">
                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">通貨 <span class="text-red-600">*</span></label>
                    <div class="mt-1">
                        <select name="currency" id="currency" required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md">
                            <option value="JPY" {{ old('currency', $settings['currency']) === 'JPY' ? 'selected' : '' }}>日本円 (JPY)</option>
                            <option value="USD" {{ old('currency', $settings['currency']) === 'USD' ? 'selected' : '' }}>米ドル (USD)</option>
                            <option value="EUR" {{ old('currency', $settings['currency']) === 'EUR' ? 'selected' : '' }}>ユーロ (EUR)</option>
                        </select>
                    </div>
                    @error('currency')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 税率 -->
                <div class="sm:col-span-2">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">税率 <span class="text-red-600">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}" step="0.01" min="0" max="1" required
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md pr-12">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                                (0〜1)
                            </span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">例: 10%の場合は0.1を入力</p>
                    @error('tax_rate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 送料 -->
                <div class="sm:col-span-2">
                    <label for="shipping_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">基本送料 <span class="text-red-600">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="shipping_fee" id="shipping_fee" value="{{ old('shipping_fee', $settings['shipping_fee']) }}" min="0" required
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md pr-12">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                                円
                            </span>
                        </div>
                    </div>
                    @error('shipping_fee')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 送料無料条件 -->
                <div class="sm:col-span-2">
                    <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">送料無料条件 <span class="text-red-600">*</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}" min="0" required
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md pr-12">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">
                                円以上
                            </span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">0の場合、常に送料がかかります</p>
                    @error('free_shipping_threshold')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- メンテナンスモード -->
                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="maintenance_mode" name="maintenance_mode" type="checkbox" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="maintenance_mode" class="font-medium text-gray-700 dark:text-gray-300">メンテナンスモード</label>
                            <p class="text-gray-500 dark:text-gray-400">有効にすると、管理者以外のユーザーはサイトにアクセスできなくなります。</p>
                        </div>
                    </div>
                    @error('maintenance_mode')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- 送信ボタン -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    設定を保存
                </button>
            </div>
        </form>
    </div>
    
    <!-- システム情報 -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg mt-6">
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                システム情報
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                サイトのシステム情報を表示します。
            </p>
        </div>
        
        <div class="border-b border-gray-200 dark:border-gray-700">
            <dl>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Laravelバージョン
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ app()->version() }}
                    </dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        PHPバージョン
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ phpversion() }}
                    </dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        環境
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ app()->environment() }}
                    </dd>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        サーバー
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $_SERVER['SERVER_SOFTWARE'] ?? '不明' }}
                    </dd>
                </div>
            </dl>
        </div>
        
        <!-- キャッシュクリアボタン -->
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="text-right">
                @csrf
                @method('PATCH')
                <input type="hidden" name="clear_cache" value="1">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    キャッシュをクリア
                </button>
            </form>
        </div>
    </div>
@endsection 