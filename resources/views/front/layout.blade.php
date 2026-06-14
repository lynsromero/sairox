<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ get_option('title', config('app.name')) }} @hasSection('title') | @yield('title') @endif</title>
    <meta name="description" content="{{ get_option('description', '') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <header class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                {{ get_option('title', config('app.name')) }}
            </a>
            <nav class="space-x-4 text-sm">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Home</a>
            </nav>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ get_option('title', config('app.name')) }}. All rights reserved.
        </div>
    </footer>
</body>
</html>
