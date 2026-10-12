<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Gluten Free')</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        document.documentElement.classList.add('js');
        try { var t = localStorage.getItem('theme'); if (t) document.documentElement.dataset.theme = t; } catch (e) {}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="glow pointer-events-none fixed -left-20 top-10 h-80 w-80 rounded-full"></div>
    <div class="glow pointer-events-none fixed -right-20 bottom-10 h-72 w-72 rounded-full"></div>

    {{-- Voltar ao site + tema --}}
    <div class="fixed inset-x-0 top-0 z-40 flex items-center justify-between px-4 py-3" style="padding-top:calc(env(safe-area-inset-top,0px) + .75rem)">
        <a href="{{ url('/') }}" class="glass inline-flex h-10 items-center gap-2 rounded-full px-4 text-sm font-semibold hover:bg-soft">← Voltar ao site</a>
        <button id="theme" type="button" aria-label="Alternar tema" class="glass grid h-10 w-10 place-items-center rounded-full hover:bg-soft">
            <svg id="sun" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            <svg id="moon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
        </button>
    </div>

    <main class="relative mx-auto flex min-h-screen w-full max-w-xl flex-col justify-center px-5 py-24">
        <a href="{{ url('/') }}" class="mx-auto mb-6 flex items-center gap-3 font-display text-xl font-extrabold">
            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="h-12 w-12 rounded-full">Gluten Free
        </a>

        <div class="glass rounded-[2rem] p-6 shadow-2xl shadow-black/10 sm:p-8">
            @yield('content')
        </div>
    </main>

    {{-- Mostrar / esconder palavra-passe --}}
    <script>
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-pass]');
            if (!b) return;
            var i = b.parentElement.querySelector('input');
            i.type = i.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>