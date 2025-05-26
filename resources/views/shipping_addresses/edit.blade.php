<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('配送先住所の編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- バリデーションエラー -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">入力内容に誤りがあります。</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('shipping_addresses.update', $shippingAddress) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- 氏名 -->
                            <div>
                                <x-input-label for="last_name" :value="__('姓')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $shippingAddress->last_name)" required autofocus />
                            </div>
                            
                            <div>
                                <x-input-label for="first_name" :value="__('名')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $shippingAddress->first_name)" required />
                            </div>
                        </div>
                        
                        <!-- 郵便番号 -->
                        <div class="mb-6">
                            <x-input-label for="postal_code" :value="__('郵便番号（ハイフンなし）')" />
                            <x-text-input id="postal_code" class="block mt-1 w-full md:w-1/3" type="text" name="postal_code" :value="old('postal_code', $shippingAddress->postal_code)" required placeholder="1234567" />
                        </div>
                        
                        <!-- 住所 -->
                        <div class="mb-6">
                            <x-input-label for="prefecture" :value="__('都道府県')" />
                            <select id="prefecture" name="prefecture" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">選択してください</option>
                                <option value="北海道" {{ old('prefecture', $shippingAddress->prefecture) == '北海道' ? 'selected' : '' }}>北海道</option>
                                <option value="青森県" {{ old('prefecture', $shippingAddress->prefecture) == '青森県' ? 'selected' : '' }}>青森県</option>
                                <option value="岩手県" {{ old('prefecture', $shippingAddress->prefecture) == '岩手県' ? 'selected' : '' }}>岩手県</option>
                                <option value="宮城県" {{ old('prefecture', $shippingAddress->prefecture) == '宮城県' ? 'selected' : '' }}>宮城県</option>
                                <option value="秋田県" {{ old('prefecture', $shippingAddress->prefecture) == '秋田県' ? 'selected' : '' }}>秋田県</option>
                                <option value="山形県" {{ old('prefecture', $shippingAddress->prefecture) == '山形県' ? 'selected' : '' }}>山形県</option>
                                <option value="福島県" {{ old('prefecture', $shippingAddress->prefecture) == '福島県' ? 'selected' : '' }}>福島県</option>
                                <option value="茨城県" {{ old('prefecture', $shippingAddress->prefecture) == '茨城県' ? 'selected' : '' }}>茨城県</option>
                                <option value="栃木県" {{ old('prefecture', $shippingAddress->prefecture) == '栃木県' ? 'selected' : '' }}>栃木県</option>
                                <option value="群馬県" {{ old('prefecture', $shippingAddress->prefecture) == '群馬県' ? 'selected' : '' }}>群馬県</option>
                                <option value="埼玉県" {{ old('prefecture', $shippingAddress->prefecture) == '埼玉県' ? 'selected' : '' }}>埼玉県</option>
                                <option value="千葉県" {{ old('prefecture', $shippingAddress->prefecture) == '千葉県' ? 'selected' : '' }}>千葉県</option>
                                <option value="東京都" {{ old('prefecture', $shippingAddress->prefecture) == '東京都' ? 'selected' : '' }}>東京都</option>
                                <option value="神奈川県" {{ old('prefecture', $shippingAddress->prefecture) == '神奈川県' ? 'selected' : '' }}>神奈川県</option>
                                <option value="新潟県" {{ old('prefecture', $shippingAddress->prefecture) == '新潟県' ? 'selected' : '' }}>新潟県</option>
                                <option value="富山県" {{ old('prefecture', $shippingAddress->prefecture) == '富山県' ? 'selected' : '' }}>富山県</option>
                                <option value="石川県" {{ old('prefecture', $shippingAddress->prefecture) == '石川県' ? 'selected' : '' }}>石川県</option>
                                <option value="福井県" {{ old('prefecture', $shippingAddress->prefecture) == '福井県' ? 'selected' : '' }}>福井県</option>
                                <option value="山梨県" {{ old('prefecture', $shippingAddress->prefecture) == '山梨県' ? 'selected' : '' }}>山梨県</option>
                                <option value="長野県" {{ old('prefecture', $shippingAddress->prefecture) == '長野県' ? 'selected' : '' }}>長野県</option>
                                <option value="岐阜県" {{ old('prefecture', $shippingAddress->prefecture) == '岐阜県' ? 'selected' : '' }}>岐阜県</option>
                                <option value="静岡県" {{ old('prefecture', $shippingAddress->prefecture) == '静岡県' ? 'selected' : '' }}>静岡県</option>
                                <option value="愛知県" {{ old('prefecture', $shippingAddress->prefecture) == '愛知県' ? 'selected' : '' }}>愛知県</option>
                                <option value="三重県" {{ old('prefecture', $shippingAddress->prefecture) == '三重県' ? 'selected' : '' }}>三重県</option>
                                <option value="滋賀県" {{ old('prefecture', $shippingAddress->prefecture) == '滋賀県' ? 'selected' : '' }}>滋賀県</option>
                                <option value="京都府" {{ old('prefecture', $shippingAddress->prefecture) == '京都府' ? 'selected' : '' }}>京都府</option>
                                <option value="大阪府" {{ old('prefecture', $shippingAddress->prefecture) == '大阪府' ? 'selected' : '' }}>大阪府</option>
                                <option value="兵庫県" {{ old('prefecture', $shippingAddress->prefecture) == '兵庫県' ? 'selected' : '' }}>兵庫県</option>
                                <option value="奈良県" {{ old('prefecture', $shippingAddress->prefecture) == '奈良県' ? 'selected' : '' }}>奈良県</option>
                                <option value="和歌山県" {{ old('prefecture', $shippingAddress->prefecture) == '和歌山県' ? 'selected' : '' }}>和歌山県</option>
                                <option value="鳥取県" {{ old('prefecture', $shippingAddress->prefecture) == '鳥取県' ? 'selected' : '' }}>鳥取県</option>
                                <option value="島根県" {{ old('prefecture', $shippingAddress->prefecture) == '島根県' ? 'selected' : '' }}>島根県</option>
                                <option value="岡山県" {{ old('prefecture', $shippingAddress->prefecture) == '岡山県' ? 'selected' : '' }}>岡山県</option>
                                <option value="広島県" {{ old('prefecture', $shippingAddress->prefecture) == '広島県' ? 'selected' : '' }}>広島県</option>
                                <option value="山口県" {{ old('prefecture', $shippingAddress->prefecture) == '山口県' ? 'selected' : '' }}>山口県</option>
                                <option value="徳島県" {{ old('prefecture', $shippingAddress->prefecture) == '徳島県' ? 'selected' : '' }}>徳島県</option>
                                <option value="香川県" {{ old('prefecture', $shippingAddress->prefecture) == '香川県' ? 'selected' : '' }}>香川県</option>
                                <option value="愛媛県" {{ old('prefecture', $shippingAddress->prefecture) == '愛媛県' ? 'selected' : '' }}>愛媛県</option>
                                <option value="高知県" {{ old('prefecture', $shippingAddress->prefecture) == '高知県' ? 'selected' : '' }}>高知県</option>
                                <option value="福岡県" {{ old('prefecture', $shippingAddress->prefecture) == '福岡県' ? 'selected' : '' }}>福岡県</option>
                                <option value="佐賀県" {{ old('prefecture', $shippingAddress->prefecture) == '佐賀県' ? 'selected' : '' }}>佐賀県</option>
                                <option value="長崎県" {{ old('prefecture', $shippingAddress->prefecture) == '長崎県' ? 'selected' : '' }}>長崎県</option>
                                <option value="熊本県" {{ old('prefecture', $shippingAddress->prefecture) == '熊本県' ? 'selected' : '' }}>熊本県</option>
                                <option value="大分県" {{ old('prefecture', $shippingAddress->prefecture) == '大分県' ? 'selected' : '' }}>大分県</option>
                                <option value="宮崎県" {{ old('prefecture', $shippingAddress->prefecture) == '宮崎県' ? 'selected' : '' }}>宮崎県</option>
                                <option value="鹿児島県" {{ old('prefecture', $shippingAddress->prefecture) == '鹿児島県' ? 'selected' : '' }}>鹿児島県</option>
                                <option value="沖縄県" {{ old('prefecture', $shippingAddress->prefecture) == '沖縄県' ? 'selected' : '' }}>沖縄県</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="city" :value="__('市区町村')" />
                            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $shippingAddress->city)" required />
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="address_line1" :value="__('番地・マンション名')" />
                            <x-text-input id="address_line1" class="block mt-1 w-full" type="text" name="address_line1" :value="old('address_line1', $shippingAddress->address_line1)" required />
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="address_line2" :value="__('建物名・部屋番号（任意）')" />
                            <x-text-input id="address_line2" class="block mt-1 w-full" type="text" name="address_line2" :value="old('address_line2', $shippingAddress->address_line2)" />
                        </div>
                        
                        <!-- 電話番号 -->
                        <div class="mb-6">
                            <x-input-label for="phone" :value="__('電話番号（ハイフンなし）')" />
                            <x-text-input id="phone" class="block mt-1 w-full md:w-1/2" type="text" name="phone" :value="old('phone', $shippingAddress->phone)" required placeholder="09012345678" />
                        </div>
                        
                        <!-- デフォルト設定 -->
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="is_default" {{ old('is_default', $shippingAddress->is_default) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">この住所をデフォルトに設定する</span>
                            </label>
                        </div>
                        
                        <!-- ボタン -->
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('shipping_addresses.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-4">
                                キャンセル
                            </a>
                            <x-primary-button>
                                {{ __('更新する') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 