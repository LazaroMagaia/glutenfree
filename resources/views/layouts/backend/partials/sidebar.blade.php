@php
    $authUser = auth()->user();
    $isAdmin = $authUser->isAdmin();
@endphp

<aside id="side"
    class="fixed inset-y-0 left-0 z-50 flex w-72
           -translate-x-full flex-col border-r border-line
           bg-surface p-5 transition-transform duration-300
           lg:translate-x-0">

    <a href="{{ route($authUser->homeRoute()) }}"
       class="flex items-center gap-3 px-2 text-lg font-extrabold">

        <img src="{{ asset('assets/images/logo.png') }}"
             alt=""
             class="h-10 w-10 rounded-full">

        Gluten Free

        <span class="ml-auto rounded-full bg-soft px-2 py-0.5
                     text-[10px] font-bold uppercase text-mute">
            {{ $isAdmin ? 'Admin' : 'Cliente' }}
        </span>
    </a>

    <nav class="mt-8 flex-1 space-y-6 overflow-y-auto">
        @if ($isAdmin)
            @include('layouts.backend.partials.sidebar-admin')
        @else
            @include('layouts.backend.partials.sidebar-user')
        @endif
    </nav>

    {{-- Mantém aqui o bloco de suporte --}}
</aside>