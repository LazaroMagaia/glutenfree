<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gluten Free · Sabor sem glúten em Moçambique')</title>
    <meta name="description" content="@yield('description', 'Farinhas, pães, massas e doces seguros para celíacos, com entrega em todo o país.')">
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Aplica o tema guardado antes de pintar (evita "flash" branco) --}}
    <script>
        document.documentElement.classList.add('js');
        try { var t = localStorage.getItem('theme'); if (t) document.documentElement.dataset.theme = t; } catch (e) {}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>

    @include('layouts.frontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.frontend.partials.footer')

    <div id="toast" class="pointer-events-none fixed bottom-6 left-1/2 z-50 -translate-x-1/2 translate-y-20 rounded-full bg-ink px-5 py-3 text-sm font-medium text-bg opacity-0 shadow-xl transition-all duration-300"></div>

    @stack('scripts')
</body>
</html>
