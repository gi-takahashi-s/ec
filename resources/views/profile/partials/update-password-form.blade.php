<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            パスワード更新
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            アカウントのセキュリティを保つため、長くてランダムなパスワードを使用してください。
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="mb-4">
            <label for="current_password" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">現在のパスワード</label>
            <input id="current_password" name="current_password" type="password" class="appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-indigo-500 @error('current_password') border-red-500 @enderror" autocomplete="current-password">
            @error('current_password')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">新しいパスワード</label>
            <input id="password" name="password" type="password" class="appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-indigo-500 @error('password') border-red-500 @enderror" autocomplete="new-password">
            @error('password')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">新しいパスワード（確認）</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-800 leading-tight focus:outline-none focus:shadow-outline focus:border-indigo-500" autocomplete="new-password">
            @error('password_confirmation')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                パスワードを更新
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="ml-3 text-sm text-green-600 dark:text-green-400"
                >パスワードを更新しました</p>
            @endif
        </div>
    </form>
</section>
