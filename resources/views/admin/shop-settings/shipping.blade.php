@extends('layouts.admin')

@section('header', '配送設定')

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
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">配送設定</span>
            </div>
        </li>
    </ol>
</nav>

<!-- 配送業者一覧 -->
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">配送業者一覧</h2>
            <a href="{{ route('admin.shop-settings.shipping.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                新規追加
            </a>
        </div>
    </div>
    <div class="p-6">
        @if($shippingCompanies->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">配送業者名</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">配送方法</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">送料設定</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ステータス</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">操作</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($shippingCompanies as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $company->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $company->method_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            @if($company->uniform_shipping_fee)
                            全国一律 ¥{{ number_format($company->uniform_fee) }}
                            @else
                            都道府県別設定
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('admin.shop-settings.shipping.toggle', $company->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $company->id }}">
                                @if($company->is_active)
                                <button type="submit" class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                    有効
                                </button>
                                @else
                                <button type="submit" class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                    無効
                                </button>
                                @endif
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.shop-settings.shipping.edit', $company->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">編集</a>
                            <button onclick="deleteCompany({{ $company->id }})" class="text-red-600 hover:text-red-900">削除</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">配送業者が登録されていません。</p>
            <a href="{{ route('admin.shop-settings.shipping.create') }}" class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded inline-block">
                最初の配送業者を追加
            </a>
        </div>
        @endif
    </div>
</div>

<!-- 削除確認モーダル -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">配送業者を削除</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">この配送業者を削除してもよろしいですか？この操作は取り消せません。</p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">キャンセル</button>
                <form id="deleteForm" method="POST" action="{{ route('admin.shop-settings.shipping.delete') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteCompanyId" name="id" value="">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteCompany(companyId) {
        document.getElementById('deleteCompanyId').value = companyId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection