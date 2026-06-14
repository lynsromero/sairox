<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', get_option('tagline', ''))">
    <title>@yield('title', get_option('site_title', 'Sairox CMS'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    @include('theme::partials.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('theme::partials.footer')
</body>
</html>
