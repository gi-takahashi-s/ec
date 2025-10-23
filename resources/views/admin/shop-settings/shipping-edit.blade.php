@extends('layouts.admin')

@section('header', '配送業者編集')

@section('content')
<!-- パンくずリスト -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.shop-settings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="mr-1 h-4 w-4 text-gray-500 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                ショップ設定
            </a>
        </li>
        <li aria-current="page">
            <a href="{{ route('admin.shop-settings.shipping') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-2">配送設定</span>
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">配送業者編集</span>
            </div>
        </li>
    </ol>
</nav>

<!-- エラーメッセージ -->
@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.shop-settings.shipping.update') }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="id" value="{{ $shippingCompany->id }}">

    <div class="space-y-6">
        <!-- 基本情報 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">基本情報</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">配送業者名 <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $shippingCompany->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">配送方法名称 <span class="text-red-500">*</span></label>
                    <input type="text" name="method_name" value="{{ old('method_name', $shippingCompany->method_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                </div>
            </div>
        </div>

        <!-- 取り扱う支払方法 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">取り扱う支払方法</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($paymentMethods as $key => $label)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="payment_methods[]" value="{{ $key }}"
                        {{ in_array($key, old('payment_methods', $shippingCompany->payment_methods ?? [])) ? 'checked' : '' }}
                        class="form-checkbox text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- お届け時間設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">お届け時間設定</h3>
            <div id="deliveryTimesContainer">
                @php
                $deliveryTimes = old('delivery_times', $shippingCompany->delivery_times ?? []);
                @endphp
                @if(count($deliveryTimes) > 0)
                @foreach($deliveryTimes as $time)
                <div class="flex items-center space-x-2 mb-2">
                    <input type="text" name="delivery_times[]" value="{{ $time }}" placeholder="例: 午前中 (9:00-12:00)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <button type="button" onclick="removeDeliveryTime(this)" class="text-red-600 hover:text-red-800">削除</button>
                </div>
                @endforeach
                @else
                <div class="flex items-center space-x-2 mb-2">
                    <input type="text" name="delivery_times[]" placeholder="例: 午前中 (9:00-12:00)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    <button type="button" onclick="removeDeliveryTime(this)" class="text-red-600 hover:text-red-800">削除</button>
                </div>
                @endif
            </div>
            <button type="button" onclick="addDeliveryTime()" class="mt-2 text-indigo-600 hover:text-indigo-800">+ 時間帯を追加</button>
        </div>

        <!-- 都道府県別送料設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">都道府県別送料設定</h3>

            <!-- 全国一律設定 -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">全国一律に設定</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="uniform_shipping_fee" value="1"
                            {{ old('uniform_shipping_fee', $shippingCompany->uniform_shipping_fee ? '1' : '0') === '1' ? 'checked' : '' }}
                            onchange="toggleUniformFee()" class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="uniform_shipping_fee" value="0"
                            {{ old('uniform_shipping_fee', $shippingCompany->uniform_shipping_fee ? '1' : '0') === '0' ? 'checked' : '' }}
                            onchange="toggleUniformFee()" class="form-radio text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">無効</span>
                    </label>
                </div>
            </div>

            <!-- 全国一律料金 -->
            <div id="uniformFeeContainer" class="mb-4 {{ old('uniform_shipping_fee', $shippingCompany->uniform_shipping_fee ? '1' : '0') === '1' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">全国一律送料（円）</label>
                <input type="number" name="uniform_fee" value="{{ old('uniform_fee', $shippingCompany->uniform_fee) }}" min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
            </div>

            <!-- 都道府県別料金 -->
            <div id="prefectureFeesContainer" class="{{ old('uniform_shipping_fee', $shippingCompany->uniform_shipping_fee ? '1' : '0') === '0' ? '' : 'hidden' }}">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($prefectures as $prefecture)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $prefecture }}</label>
                        <input type="number" name="prefecture_fees[{{ $prefecture }}]"
                            value="{{ old('prefecture_fees.' . $prefecture, $shippingCompany->prefecture_fees[$prefecture] ?? '') }}"
                            min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 備考 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">備考</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">配送に関する備考</label>
                <textarea name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">{{ old('notes', $shippingCompany->notes) }}</textarea>
            </div>
        </div>

        <!-- 有効/無効 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shippingCompany->is_active) ? 'checked' : '' }} class="form-checkbox text-indigo-600 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-700">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">この配送業者を有効にする</span>
            </label>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.shop-settings.shipping') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">キャンセル</a>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">保存</button>
    </div>
</form>

<script>
    function addDeliveryTime() {
        const container = document.getElementById('deliveryTimesContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 mb-2';
        div.innerHTML = `
        <input type="text" name="delivery_times[]" placeholder="例: 午前中 (9:00-12:00)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
        <button type="button" onclick="removeDeliveryTime(this)" class="text-red-600 hover:text-red-800">削除</button>
    `;
        container.appendChild(div);
    }

    function removeDeliveryTime(button) {
        button.parentElement.remove();
    }

    function toggleUniformFee() {
        const uniformEnabled = document.querySelector('input[name="uniform_shipping_fee"]:checked').value === '1';
        const uniformContainer = document.getElementById('uniformFeeContainer');
        const prefectureContainer = document.getElementById('prefectureFeesContainer');

        if (uniformEnabled) {
            uniformContainer.classList.remove('hidden');
            prefectureContainer.classList.add('hidden');
        } else {
            uniformContainer.classList.add('hidden');
            prefectureContainer.classList.remove('hidden');
        }
    }
</script>
@endsection