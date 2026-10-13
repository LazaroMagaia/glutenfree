
@extends('layouts.backend')

@section('title', 'Gluten Free · Novo Utilizador')

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
                Novo utilizador
            </h1>

            <p class="mt-2 text-sm text-mute">
                Preenche os dados para criar uma nova conta na plataforma.
            </p>
        </div>

        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-soft px-3 py-2 text-xs font-bold text-mute">
           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus preview-icon"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Nova conta
        </span>
    </div>

    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/5 p-4 text-sm text-red-600">
            <div class="flex items-center gap-2 font-bold">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert preview-icon"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                Verifica os campos do formulário.
            </div>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}"
          method="POST"
          class="space-y-6">
        @csrf

        {{-- Dados pessoais --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                    </span>

                    <div>
                        <h2 class="font-bold text-ink">Dados pessoais</h2>
                        <p class="mt-1 text-xs text-mute">
                            Informações básicas do utilizador.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                <div class="sm:col-span-2">
                    <label for="name" class="mb-2 block text-sm font-semibold text-ink">
                        Nome completo <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name') }}"
                           placeholder="Ex.: João Manuel"
                           autocomplete="name"
                           required
                           autofocus
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                            focus:border-brand focus:ring-brand @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-ink">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input id="email"
                           name="email"
                           type="email"
                           value="{{ old('email') }}"
                           placeholder="nome@exemplo.com"
                           autocomplete="email"
                           required
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute 
                           focus:border-brand focus:ring-brand @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-semibold text-ink">
                        Telefone
                    </label>

                    <input id="phone"
                           name="phone"
                           type="tel"
                           value="{{ old('phone') }}"
                           placeholder="Ex.: 841234567"
                           autocomplete="tel"
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand 
                           focus:ring-brand @error('phone') border-red-500 @enderror">

                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Conta e permissões --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>

                    <div>
                        <h2 class="font-bold text-ink">Conta e permissões</h2>
                        <p class="mt-1 text-xs text-mute">
                            Define o tipo de acesso à plataforma.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                <div class="sm:col-span-2">
                    <label for="role" class="mb-2 block text-sm font-semibold text-ink">
                        Papel do utilizador <span class="text-red-500">*</span>
                    </label>

                    <select id="role"
                            name="role"
                            required
                            class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink focus:border-brand focus:ring-brand 
                            @error('role') border-red-500 @enderror">
                        <option value="">Seleciona um papel</option>
                        <option value="user" @selected(old('role', 'user') === 'user')>
                            Cliente
                        </option>
                        <option value="admin" @selected(old('role') === 'admin')>
                            Administrador
                        </option>
                    </select>

                    @error('role')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-mute">
                        Os administradores podem ter acesso às funcionalidades de gestão.
                        A atribuição deste papel deve ser autorizada no backend.
                    </p>
                </div>
                {{-- Palavra-passe padrão --}}
                <div class="sm:col-span-2">
                    <div class="flex items-start gap-3 rounded-xl border border-amber-500/30 bg-amber-500/5 p-4">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                       class="lucide lucide-key-round preview-icon"><path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>

                        <div>
                            <p class="text-sm font-bold text-ink">
                                Palavra-passe inicial automática
                            </p>

                            <p class="mt-1 text-sm text-mute">
                                A conta será criada com uma palavra-passe inicial.
                                O utilizador deverá alterá-la após o primeiro acesso.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-bold text-ink transition hover:bg-soft">
                Cancelar
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm 
                    font-bold text-brandink shadow-sm transition hover:opacity-90">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus preview-icon"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Criar utilizador
            </button>
        </div>

    </form>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.togglePassword);

            if (!input) return;

            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';

            button.setAttribute(
                'aria-label',
                showing ? 'Mostrar palavra-passe' : 'Ocultar palavra-passe'
            );

            button.innerHTML = showing
                ? '<i data-lucide="eye" class="h-4 w-4"></i>'
                : '<i data-lucide="eye-off" class="h-4 w-4"></i>';

            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    });
});
</script>
@endpush