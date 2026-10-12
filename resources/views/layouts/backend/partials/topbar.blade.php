@php
    $authUser = auth()->user();
    $roleLabel = $authUser->isAdmin() ? 'Administrador' : 'Cliente';
@endphp

<header class="sticky top-0 z-30 border-b border-line backdrop-blur" style="background:color-mix(in srgb,var(--bg) 85%,transparent);padding-top:env(safe-area-inset-top,0px)">
    <div class="flex items-center gap-3 px-4 py-3 sm:px-8">
        <button id="openSide" type="button" aria-label="Menu" class="grid h-10 w-10 shrink-0 place-items-center rounded-full border border-line bg-surface lg:hidden">
            @include('layouts.backend.partials.icon', ['n' => 'menu'])
        </button>

        <label class="relative hidden max-w-md flex-1 sm:block">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-mute">@include('layouts.backend.partials.icon', ['n' => 'search'])</span>
            <input placeholder="Pesquisar…" class="w-full rounded-full border border-line bg-surface py-2.5 pl-11 pr-4 text-sm outline-none focus:border-accent focus:ring-0">
        </label>

        <div class="ml-auto flex items-center gap-2">
            <button id="theme" type="button" aria-label="Alternar tema" class="grid h-10 w-10 place-items-center rounded-full border border-line bg-surface">
                <span id="sun" class="hidden">@include('layouts.backend.partials.icon', ['n' => 'sun'])</span>
                <span id="moon">@include('layouts.backend.partials.icon', ['n' => 'moon'])</span>
            </button>

            <button type="button" aria-label="Notificações" class="relative grid h-10 w-10 place-items-center rounded-full border border-line bg-surface">
                @include('layouts.backend.partials.icon', ['n' => 'bell'])
                <i class="absolute right-2.5 top-2.5 h-2 w-2 rounded-full bg-accent"></i>
            </button>

            <div class="flex items-center gap-3 rounded-full border border-line bg-surface py-1 pl-1 pr-4">
                <span class="grid h-8 w-8 place-items-center rounded-full bg-brand text-sm font-bold text-brandink">{{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}</span>
                <span class="hidden text-sm leading-tight sm:block"><b class="block">{{ explode(' ', trim($authUser->name))[0] }}</b><span class="text-xs text-mute">{{ $roleLabel }}</span></span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" aria-label="Sair" title="Sair" class="grid h-10 w-10 place-items-center rounded-full border border-line bg-surface hover:bg-soft">
                    @include('layouts.backend.partials.icon', ['n' => 'logout'])
                </button>
            </form>
        </div>
    </div>
</header>
