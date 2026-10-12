@php
    $authUser = auth()->user();
    $isAdmin  = $authUser->isAdmin();

    // [ícone, texto, nome da rota|null]
    $groups = $isAdmin
        ? [
            ['Principal', [['home', 'Dashboard', 'admin.index'], ['bag', 'Encomendas', null], ['box', 'Produtos', null], ['users', 'Clientes', null]]],
            ['Conteúdo',  [['book', 'Receitas', null], ['star', 'Reviews', null], ['help', 'FAQ', null]]],
            ['Sistema',   [['sliders', 'Definições', null]]],
        ]
        : [
            ['Conta', [['home', 'Início', 'user.index'], ['bag', 'As minhas encomendas', null], ['users', 'O meu perfil', null]]],
        ];
@endphp

<aside id="side" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-line bg-surface p-5 transition-transform duration-300 lg:translate-x-0" style="padding-top:calc(env(safe-area-inset-top,0px) + 1.25rem)">
    <a href="{{ route($authUser->homeRoute()) }}" class="flex items-center gap-3 px-2 text-lg font-extrabold">
        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="h-10 w-10 rounded-full">Gluten Free
        <span class="ml-auto rounded-full bg-soft px-2 py-0.5 text-[10px] font-bold uppercase text-mute">{{ $isAdmin ? 'Admin' : 'Cliente' }}</span>
    </a>

    <nav class="mt-8 flex-1 space-y-6 overflow-y-auto">
        @foreach ($groups as [$title, $items])
            <div>
                <p class="mb-2 px-3 text-[11px] font-bold uppercase tracking-widest text-mute">{{ $title }}</p>
                <div class="space-y-1">
                    @foreach ($items as [$icon, $label, $route])
                        <a href="{{ $route ? route($route) : '#' }}" class="nav-a {{ $route && request()->routeIs($route) ? 'on' : '' }}">
                            @include('layouts.backend.partials.icon', ['n' => $icon]){{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="card mt-4 bg-soft p-4 text-sm">
        <p class="font-bold">Precisa de ajuda?</p>
        <p class="mt-1 text-xs text-mute">Fale com o suporte por WhatsApp.</p>
        <a href="#" class="mt-3 inline-block rounded-full bg-brand px-4 py-2 text-xs font-semibold text-brandink">Contactar</a>
    </div>
</aside>
