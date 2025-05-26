<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('メール認証') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('ご登録ありがとうございます。ご利用を開始する前に、先ほどお送りしたメールアドレス認証のリンクをクリックして、メールアドレスの確認をお願いします。メールが届いていない場合は、再送信をリクエストすることができます。') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            {{ __('新しい認証リンクをご登録いただいたメールアドレスに送信しました。') }}
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-6">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <x-primary-button>
                                {{ __('認証メールを再送信') }}
                            </x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('ログアウト') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
