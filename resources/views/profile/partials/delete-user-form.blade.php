<section>
    <div class="max-w-xl">
        <p class="text-sm text-gray-600 mb-4">
            アカウントを削除すると、アカウントに関連するすべてのリソースとデータが完全に削除されます。アカウントを削除する前に、保持したいデータや情報をダウンロードしてください。
        </p>

        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
            アカウントを削除する
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                本当にアカウントを削除しますか？
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                アカウントを削除すると、アカウントに関連するすべてのリソースとデータが完全に削除されます。アカウントを完全に削除することを確認するために、パスワードを入力してください。
            </p>

            <div class="mt-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">パスワード</label>
                <input id="password" name="password" type="password" class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mt-1 block w-3/4" placeholder="パスワード">
                @error('password', 'userDeletion')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button x-on:click="$dispatch('close')" type="button" class="text-gray-700 bg-white hover:bg-gray-100 py-2 px-4 mr-3 rounded border border-gray-300">
                    キャンセル
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    アカウントを削除する
                </button>
            </div>
        </form>
    </x-modal>
</section>
