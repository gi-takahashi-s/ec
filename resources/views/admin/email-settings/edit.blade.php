@extends("layouts.admin")

@section('header', 'メール設定 - ' . $allTypes[$type])

@section('content')
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.email-settings.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                メール設定
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $allTypes[$type] }}</span>
            </div>
        </li>
    </ol>
</nav>

<div class="bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $allTypes[$type] }}</h1>
            <div class="flex items-center">
                <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">有効</span>
                <label class="inline-flex relative items-center cursor-pointer">
                    <input type="checkbox" 
                           id="is_active_toggle"
                           class="sr-only peer" 
                           {{ $emailSetting && $emailSetting->is_active ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.email-settings.update-single', $type) }}" method="POST" class="p-6">
        @csrf
        @method('PATCH')

        <div class="space-y-6">
            <!-- 件名 -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    件名 <span class="text-red-600">*</span>
                </label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       value="{{ old('subject', $emailSetting ? $emailSetting->subject : '') }}"
                       required
                       class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- 本文 -->
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    本文 <span class="text-red-600">*</span>
                </label>
                <textarea id="body" 
                          name="body" 
                          rows="15"
                          required
                          class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">{{ old('body', $emailSetting ? $emailSetting->body : '') }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                @php
                    $specialNote = \App\Helpers\EmailTemplateHelper::getSpecialNotes($type);
                    $variables = \App\Helpers\EmailTemplateHelper::getVariablesByType($type);
                    $conditionalUsage = \App\Helpers\EmailTemplateHelper::getConditionalUsage();
                @endphp

                @if($specialNote)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $specialNote }}
                    </p>
                @endif

                @if(count($variables) > 0)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mt-4">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">利用可能なテンプレート変数</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach($variables as $variable => $description)
                                <div><code>{{ $variable }}</code> - {{ $description }}</div>
                            @endforeach
                        </div>
                        
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mt-4 mb-2">条件分岐の使用方法</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                            <p>{{ $conditionalUsage['description'] }}</p>
                            <div class="bg-gray-100 dark:bg-gray-600 p-2 rounded font-mono text-xs">
                                @foreach($conditionalUsage['examples'] as $example)
                                    @if($example === '')
                                        <br>
                                    @else
                                        {{ $example }}<br>
                                    @endif
                                @endforeach
                            </div>
                            @if($type === 'shipping_notification')
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    ※ 配送情報（発送日、配送業者、お届け予定日など）が未設定の場合は空白で表示されます
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- 有効/無効の隠しフィールド -->
            <input type="hidden" id="is_active_hidden" name="is_active" value="{{ $emailSetting && $emailSetting->is_active ? '1' : '0' }}">
        </div>

        <!-- 保存ボタン -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('admin.email-settings.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                戻る
            </a>
            <button type="submit" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                設定を保存
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('is_active_toggle');
    const hiddenField = document.getElementById('is_active_hidden');
    
    toggle.addEventListener('change', function() {
        hiddenField.value = this.checked ? '1' : '0';
    });
});
</script>
@endsection
