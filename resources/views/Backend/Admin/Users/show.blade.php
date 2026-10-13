@extends('layouts.backend')

@section('title', 'Gluten Free · ' . $user->name)

@php
    $roleValue = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;
    $isAdmin = $roleValue === 'admin';
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

@section('content')
<section class="mx-auto max-w-5xl space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-mute transition hover:text-ink">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left preview-icon"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Voltar aos utilizadores
            </a>

            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Detalhes do utilizador
            </h1>

            <p class="mt-2 text-sm text-mute">
                Consulta as informações da conta e o tipo de acesso.
            </p>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row">
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-pen preview-icon"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg>
                Editar utilizador
            </a>
        </div>
    </div>

    {{-- Resumo --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:p-6">
            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-soft text-xl font-extrabold text-ink">
                {{ $initials }}
            </span>

            <div class="min-w-0 flex-1">
                <h2 class="truncate text-xl font-extrabold tracking-tight text-ink">
                    {{ $user->name }}
                </h2>
                <p class="mt-1 truncate text-sm text-mute">
                    {{ $user->email }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-2 text-xs font-bold
                {{ $isAdmin ? 'bg-brand text-brandink' : 'bg-soft text-mute' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                {{ $isAdmin ? 'Administrador' : 'Cliente' }}
            </span>
        </div>
    </div>

    {{-- Dados pessoais --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">
        <div class="border-b border-line p-5 sm:p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                </span>

                <div>
                    <h2 class="font-bold text-ink">Dados pessoais</h2>
                    <p class="mt-1 text-xs text-mute">Informações básicas do utilizador.</p>
                </div>
            </div>
        </div>

        <dl class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold text-mute">Nome completo</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">{{ $user->name }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-mute">Email</dt>
                <dd class="mt-1 break-all text-sm font-semibold text-ink">{{ $user->email }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-mute">Telefone</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">
                    {{ $user->phone ?: 'Não indicado' }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- Conta e permissões --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">
        <div class="border-b border-line p-5 sm:p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                </span>

                <div>
                    <h2 class="font-bold text-ink">Conta e permissões</h2>
                    <p class="mt-1 text-xs text-mute">Tipo de acesso e datas da conta.</p>
                </div>
            </div>
        </div>

        <dl class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
            <div>
                <dt class="text-xs font-semibold text-mute">Papel</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">
                    {{ $isAdmin ? 'Administrador' : 'Cliente' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-mute">ID</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">#{{ $user->id }}</dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-mute">Conta criada em</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">
                    {{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-semibold text-mute">Última actualização</dt>
                <dd class="mt-1 text-sm font-semibold text-ink">
                    {{ $user->updated_at?->format('d/m/Y H:i') ?? '—' }}
                </dd>
            </div>
        </dl>
    </div>

    {{-- Zona de perigo --}}
    <div class="overflow-hidden rounded-2xl border border-red-500/30 bg-red-500/5">
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <h2 class="font-bold text-red-600">Eliminar utilizador</h2>
                <p class="mt-1 text-sm text-mute">
                    Esta acção remove a conta de forma permanente e não pode ser desfeita.
                </p>
            </div>

            <form action="{{ route('admin.users.destroy', $user) }}"
                  method="POST"
                  onsubmit="return confirm('Eliminar {{ e($user->name) }}? Esta acção não pode ser desfeita.')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-500/40 px-5 py-3 text-sm font-bold text-red-600 transition hover:bg-red-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 preview-icon"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    Eliminar utilizador
                </button>
            </form>
        </div>
    </div>

</section>
@endsection