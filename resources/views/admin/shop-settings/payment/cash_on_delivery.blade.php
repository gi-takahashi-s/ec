@extends('layouts.admin')

@section('header', '代金引換決済設定')

@section('content')
<!-- パンくずリスト -->
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.shop-settings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                </svg>
                ショップ設定
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <a href="{{ route('admin.shop-settings.payment') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">決済設定</a>
            </div>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">代金引換設定</span>
            </div>
        </li>
    </ol>
</nav>

<form action="{{ route('admin.shop-settings.payment.method.update', 'cash_on_delivery') }}" method="POST">
    @csrf
    @method('PATCH')

    <!-- エラー表示 -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        入力に問題があります
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- 基本設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">基本設定</h3>

                <div class="space-y-4">
                    <!-- 有効/無効 -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_enabled" id="is_enabled"
                                {{ $paymentMethod->is_enabled ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3">
                            <label for="is_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                代金引換決済を有効にする
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                チェックを入れると、お客様が代金引換決済を選択できるようになります
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 代金引換設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">代金引換設定</h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- 代引手数料レンジ設定 -->
                    <div class="col-span-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            代引手数料設定 <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            注文金額に応じた代引手数料を設定してください。レンジが重複しないように設定してください。
                        </p>
                        
                        <div id="cod-fee-ranges">
                            @php
                                // データベースから保存された値を優先し、空の場合のみデフォルト値を使用
                                $savedRanges = $paymentMethod->settings['cod_fee_ranges'] ?? null;
                                $defaultRanges = [
                                    ['min_amount' => 0, 'max_amount' => 9999, 'fee' => 330],
                                    ['min_amount' => 10000, 'max_amount' => 29999, 'fee' => 440],
                                    ['min_amount' => 30000, 'max_amount' => '', 'fee' => 660]
                                ];
                                
                                // old()の値を最優先、次に保存された値、最後にデフォルト値
                                if (old('settings.cod_fee_ranges')) {
                                    $codFeeRanges = old('settings.cod_fee_ranges');
                                } elseif (!empty($savedRanges)) {
                                    $codFeeRanges = $savedRanges;
                                } else {
                                    $codFeeRanges = $defaultRanges;
                                }
                            @endphp
                            
                            @foreach($codFeeRanges as $index => $range)
                            <div class="cod-fee-range border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-3" data-index="{{ $index }}">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">レンジ {{ $index + 1 }}</h4>
                                    @if($index > 0)
                                    <button type="button" class="remove-range text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">最小金額（円）</label>
                                        <input type="number" name="settings[cod_fee_ranges][{{ $index }}][min_amount]" 
                                               value="{{ $range['min_amount'] ?? '' }}"
                                               class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               min="0" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">最大金額（円）</label>
                                        <input type="number" name="settings[cod_fee_ranges][{{ $index }}][max_amount]" 
                                               value="{{ $range['max_amount'] ?? '' }}"
                                               class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               min="0" placeholder="無制限の場合は空欄">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">手数料（円）</label>
                                        <input type="number" name="settings[cod_fee_ranges][{{ $index }}][fee]" 
                                               value="{{ $range['fee'] ?? '' }}"
                                               class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               min="0" required>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="button" id="add-range" class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            レンジを追加
                        </button>
                        
                        @error('settings.cod_fee_ranges')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 最低注文金額 -->
                    <div>
                        <label for="min_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            最低注文金額（円）
                        </label>
                        <input type="number" name="settings[min_amount]" id="min_amount"
                            value="{{ old('settings.min_amount', $paymentMethod->settings['min_amount'] ?? 0) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.min_amount') border-red-300 @enderror"
                            min="0">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            代金引換を利用できる最低注文金額（0円の場合は制限なし）
                        </p>
                        @error('settings.min_amount')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 最高注文金額 -->
                    <div>
                        <label for="max_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            最高注文金額（円）
                        </label>
                        <input type="number" name="settings[max_amount]" id="max_amount"
                            value="{{ old('settings.max_amount', $paymentMethod->settings['max_amount'] ?? 300000) }}"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.max_amount') border-red-300 @enderror"
                            min="0">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            代金引換を利用できる最高注文金額（通常30万円まで）
                        </p>
                        @error('settings.max_amount')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 配送業者 -->
                    <div>
                        <label for="delivery_company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            配送業者
                        </label>
                        <select name="settings[delivery_company]" id="delivery_company"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.delivery_company') border-red-300 @enderror">
                            <option value="">選択してください</option>
                            <option value="yamato" {{ old('settings.delivery_company', $paymentMethod->settings['delivery_company'] ?? '') == 'yamato' ? 'selected' : '' }}>ヤマト運輸</option>
                            <option value="sagawa" {{ old('settings.delivery_company', $paymentMethod->settings['delivery_company'] ?? '') == 'sagawa' ? 'selected' : '' }}>佐川急便</option>
                            <option value="japan_post" {{ old('settings.delivery_company', $paymentMethod->settings['delivery_company'] ?? '') == 'japan_post' ? 'selected' : '' }}>日本郵便</option>
                            <option value="other" {{ old('settings.delivery_company', $paymentMethod->settings['delivery_company'] ?? '') == 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            代金引換サービスを提供する配送業者
                        </p>
                        @error('settings.delivery_company')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 注意事項設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">注意事項設定</h3>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        お客様への注意事項
                    </label>
                    <textarea name="settings[notes]" id="notes" rows="4"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('settings.notes') border-red-300 @enderror"
                        placeholder="代金引換時の注意事項をご記入ください">{{ old('settings.notes', $paymentMethod->settings['notes'] ?? "・商品受け取り時に代金をお支払いください。\n・代引手数料が別途かかります。\n・お釣りのないようご準備ください。\n・不在の場合は再配達となります。") }}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        注文確認画面やメールに表示される注意事項
                    </p>
                    @error('settings.notes')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 配送制限設定 -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">配送制限設定</h3>

                <div class="space-y-4">
                    <!-- 時間指定 -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="settings[allow_time_designation]" id="allow_time_designation"
                                {{ old('settings.allow_time_designation', $paymentMethod->settings['allow_time_designation'] ?? true) ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
                        </div>
                        <div class="ml-3">
                            <label for="allow_time_designation" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                配送時間指定を許可する
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                代金引換時の配送時間指定の可否
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 保存ボタン -->
    <div class="mt-6 flex items-center justify-end space-x-3">
        <a href="{{ route('admin.shop-settings.payment') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            キャンセル
        </a>
        <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            保存する
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rangeIndex = document.querySelectorAll('.cod-fee-range').length;
    
    // フォーム送信のデバッグ
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            
            // フォームデータを収集してログ出力
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            console.log('Form data:', data);
        });
    }
    
    // 新しいレンジ要素を作成
    function createRangeElement(index) {
        const div = document.createElement('div');
        div.className = 'cod-fee-range border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-3';
        div.setAttribute('data-index', index);
        
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">レンジ ${index + 1}</h4>
                <button type="button" class="remove-range text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">最小金額（円）</label>
                    <input type="number" name="settings[cod_fee_ranges][${index}][min_amount]" 
                           value=""
                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           min="0" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">最大金額（円）</label>
                    <input type="number" name="settings[cod_fee_ranges][${index}][max_amount]" 
                           value=""
                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           min="0" placeholder="無制限の場合は空欄">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">手数料（円）</label>
                    <input type="number" name="settings[cod_fee_ranges][${index}][fee]" 
                           value=""
                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           min="0" required>
                </div>
            </div>
        `;
        
        return div;
    }
    
    // レンジ番号を更新
    function updateRangeNumbers() {
        const ranges = document.querySelectorAll('.cod-fee-range');
        ranges.forEach((range, index) => {
            const title = range.querySelector('h4');
            if (title) {
                title.textContent = `レンジ ${index + 1}`;
            }
            
            // name属性も更新
            const inputs = range.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
            
            // 削除ボタンの表示/非表示（最初のレンジは削除できない）
            const removeButton = range.querySelector('.remove-range');
            if (removeButton) {
                removeButton.style.display = index === 0 ? 'none' : 'block';
            }
        });
    }
    
    // レンジ追加ボタンのイベントリスナー
    const addRangeButton = document.getElementById('add-range');
    if (addRangeButton) {
        addRangeButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const rangesContainer = document.getElementById('cod-fee-ranges');
            if (rangesContainer) {
                const newRange = createRangeElement(rangeIndex);
                rangesContainer.appendChild(newRange);
                rangeIndex++;
                updateRangeNumbers();
            }
        });
    }
    
    // レンジ削除ボタンのイベントリスナー（イベント委譲）
    const rangesContainer = document.getElementById('cod-fee-ranges');
    if (rangesContainer) {
        rangesContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-range')) {
                e.preventDefault();
                const rangeElement = e.target.closest('.cod-fee-range');
                if (rangeElement) {
                    rangeElement.remove();
                    updateRangeNumbers();
                }
            }
        });
    }
    
    // 初期化時に削除ボタンの表示を調整
    updateRangeNumbers();
});
</script>
@endsection 