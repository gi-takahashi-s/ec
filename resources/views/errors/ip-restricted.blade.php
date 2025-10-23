<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アクセス制限 - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8">
        <div class="text-center">
            <!-- エラーアイコン -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            
            <!-- エラータイトル -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                アクセス制限
            </h1>
            
            <!-- エラーメッセージ -->
            <p class="text-gray-600 mb-6">
                {{ $message ?? 'このサイトへのアクセスが制限されています。' }}
            </p>
            
            <!-- エラーコード -->
            <p class="text-sm text-gray-500 mb-6">
                エラーコード: 403 - Forbidden
            </p>
            
            <!-- 詳細情報 -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="text-sm text-gray-700">
                    @if(($type ?? 'frontend') === 'admin')
                        <p class="mb-2"><strong>管理画面IP制限</strong></p>
                        <p>このIPアドレスからは管理画面にアクセスできません。</p>
                        <p class="mt-2">アクセスが必要な場合は、システム管理者にお問い合わせください。</p>
                    @else
                        <p class="mb-2"><strong>サイトアクセス制限</strong></p>
                        <p>このIPアドレスからはサイトにアクセスできません。</p>
                        <p class="mt-2">アクセスが必要な場合は、サイト管理者にお問い合わせください。</p>
                    @endif
                </div>
            </div>
            
            <!-- 戻るボタン -->
            @if(($type ?? 'frontend') !== 'admin')
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ホームページに戻る
                </a>
            @endif
        </div>
        
        <!-- フッター情報 -->
        <div class="mt-8 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                @if(config('app.env') === 'local')
                    <span class="text-green-600">開発環境ではIP制限は無効です</span>
                @else
                    アクセス時刻: {{ now()->format('Y年m月d日 H:i:s') }}
                @endif
            </p>
        </div>
    </div>
</body>
</html> 