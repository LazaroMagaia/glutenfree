<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Gluten Free · Painel')</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        try { var t = localStorage.getItem('theme'); if (t) document.documentElement.dataset.theme = t; } catch (e) {}
    </script>

    @vite(['resources/css/backend.css', 'resources/js/backend.js'])
    @stack('head')
</head>
<body>

    <div id="overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

    @include('layouts.backend.partials.sidebar')

    <div class="lg:pl-72">
        @include('layouts.backend.partials.topbar')

        <main class="space-y-6 px-4 py-8 sm:px-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
