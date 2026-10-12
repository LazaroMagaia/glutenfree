@php
    $links = [
        'produtos' => 'Produtos',
        'receitas' => 'Receitas',
        'contacto' => 'Contacto',
    ];
    $home  = url('/');
    $login = url('/login'); // troca por route('login') quando a rota existir
@endphp

<header class="fixed inset-x-0 top-0 z-40 px-4" style="padding-top:calc(env(safe-area-inset-top,0px) + .75rem)">
    <div class="glass mx-auto flex max-w-5xl items-center justify-between rounded-full py-2 pl-3 pr-2 shadow-lg shadow-black/5">
        <a href="{{ $home }}" class="flex items-center gap-2 font-display text-lg font-extrabold">
            <img src="{{ asset('assets/images/logo.png') }}" alt="" class="h-9 w-9 rounded-full">Gluten Free
        </a>

        <nav class="hidden items-center gap-1 text-sm font-medium md:flex">
            @foreach ($links as $id => $label)
                <a class="rounded-full px-4 py-2 text-mute hover:bg-soft hover:text-ink" href="{{ $home }}#{{ $id }}">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <button id="theme" aria-label="Alternar tema" class="grid h-10 w-10 place-items-center rounded-full hover:bg-soft">
                <svg id="sun" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
                <svg id="moon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
            </button>

            {{-- Entrar --}}
            <a href="{{ $login }}" aria-label="Entrar" title="Entrar" class="grid h-10 w-10 place-items-center rounded-full hover:bg-soft">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
            </a>

            <a href="{{ $home }}#produtos" class="hidden h-10 items-center gap-2 rounded-full bg-brand pl-4 pr-1.5 text-sm font-semibold text-brandink md:flex">
                Carrinho <span class="cc grid h-7 min-w-7 place-items-center rounded-full bg-accent px-1 text-xs text-black">0</span>
            </a>

            <button id="menu" aria-label="Menu" class="grid h-10 w-10 place-items-center rounded-full hover:bg-soft md:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>

    <nav id="mobile" class="glass mx-auto mt-2 hidden max-w-5xl rounded-3xl bg-surface p-3 text-base font-medium shadow-2xl shadow-black/20 md:hidden" style="background:var(--surface)">
        @foreach ($links as $id => $label)
            <a class="block rounded-xl px-4 py-3" href="{{ $home }}#{{ $id }}">{{ $label }}</a>
        @endforeach
        <a href="{{ $home }}#produtos" class="mt-2 flex items-center justify-between rounded-2xl bg-brand px-5 py-3.5 font-semibold text-brandink">
            Carrinho <span class="cc grid h-7 min-w-7 place-items-center rounded-full bg-accent px-1 text-xs text-black">0</span>
        </a>
    </nav>
</header>