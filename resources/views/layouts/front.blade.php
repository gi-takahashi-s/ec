<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ECサイト')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">
                    <a href="{{ route('welcome') }}">ECサイト</a>
                </h1>
                <nav>
                    <ul class="flex space-x-4 items-center">
                        <li><a href="{{ route('welcome') }}" class="hover:underline">ホーム</a></li>
                        <li><a href="{{ route('front.sub') }}" class="hover:underline">サブページ</a></li>
                        
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:underline">ログイン</a></li>
                            <li><a href="{{ route('register') }}" class="bg-white text-blue-600 px-3 py-1 rounded hover:bg-blue-100">会員登録</a></li>
                        @else
                            <li class="relative group">
                                <button class="flex items-center hover:underline">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 hidden group-hover:block mt-2 py-2 w-48 bg-white rounded-md shadow-lg z-10">
                                    <a href="{{ route('mypage') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">マイページ</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">プロフィール編集</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">ログアウト</button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; {{ date('Y') }} ECサイト All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html> 