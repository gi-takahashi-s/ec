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
                    <a href="{{ route('front.index') }}">ECサイト</a>
                </h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{ route('front.index') }}" class="hover:underline">ホーム</a></li>
                        <li><a href="{{ route('front.sub') }}" class="hover:underline">サブページ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4">
            <p class="text-center">&copy; {{ date('Y') }} ECサイト All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html> 