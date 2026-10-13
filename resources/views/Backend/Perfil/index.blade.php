@extends('layouts.backend')

@section('title', 'Gluten Free · O meu perfil')

@php
    $roleValue = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;
    $isAdmin = $roleValue === 'admin';
    $initials = collect(explode(' ', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
    $passwordErrors = $errors->getBag('updatePassword');
@endphp

@section('content')
    @include('components.flash')
    @include('Backend.Admin.Users.Modals.delete')
<section class="mx-auto max-w-5xl space-y-6">

    {{-- Cabeçalho --}}
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            O meu perfil
        </h1>

        <p class="mt-2 text-sm text-mute">
            Actualiza os teus dados pessoais e a palavra-passe de acesso.
        </p>
    </div>

    {{-- Resumo --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">
        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:p-6">
            @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}"
                     alt="Foto de {{ $user->name }}"
                     class="h-16 w-16 shrink-0 rounded-2xl object-cover">
            @else
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-soft text-xl font-extrabold text-ink">
                    {{ $initials }}
                </span>
            @endif

            <div class="min-w-0 flex-1">
                <h2 class="truncate text-xl font-extrabold tracking-tight text-ink">
                    {{ $user->name }}
                </h2>
                <p class="mt-1 truncate text-sm text-mute">
                    {{ $user->email }}
                </p>
                <p class="mt-1 text-xs text-mute">
                    Conta criada em {{ $user->created_at?->format('d/m/Y') ?? '—' }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-2 text-xs font-bold
                {{ $isAdmin ? 'bg-brand text-brandink' : 'bg-soft text-mute' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                {{ $isAdmin ? 'Administrador' : 'Cliente' }}
            </span>
        </div>
    </div>

    {{-- Erros do formulário de dados pessoais --}}
    @if ($errors->default->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/5 p-4 text-sm text-red-600">
            <div class="flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert preview-icon"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                Verifica os campos do formulário.
            </div>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->default->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Dados pessoais --}}
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                    </span>

                    <div>
                        <h2 class="font-bold text-ink">Dados pessoais</h2>
                        <p class="mt-1 text-xs text-mute">Estas informações identificam a tua conta.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                {{-- Foto de perfil --}}
                <div class="sm:col-span-2">
                    <span class="mb-2 block text-sm font-semibold text-ink">Foto de perfil</span>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div class="h-20 w-20 shrink-0">
                            <img id="avatar-preview"
                                 src="{{ $user->avatar_url }}"
                                 alt="Foto de {{ $user->name }}"
                                 class="h-20 w-20 rounded-2xl object-cover {{ $user->avatar_url ? '' : 'hidden' }}">

                            <span id="avatar-initials"
                                  class="flex h-20 w-20 items-center justify-center rounded-2xl bg-soft text-2xl font-extrabold text-ink {{ $user->avatar_url ? 'hidden' : '' }}">
                                {{ $initials }}
                            </span>
                        </div>

                        <div class="min-w-0 space-y-2">
                            <input id="avatar"
                                   name="avatar"
                                   type="file"
                                   accept="image/png,image/jpeg,image/webp"
                                   class="block w-full text-sm text-mute file:mr-3 file:cursor-pointer file:rounded-xl file:border-0 file:bg-soft file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-ink hover:file:opacity-90">

                            <p class="text-xs text-mute">JPG, PNG ou WebP, até 2 MB.</p>

                            @if ($user->avatar)
                                <label class="inline-flex cursor-pointer items-center gap-2 text-xs text-mute">
                                    <input id="remove-avatar"
                                           name="remove_avatar"
                                           type="checkbox"
                                           value="1"
                                           class="rounded border-line text-brand focus:ring-brand">
                                    Remover a foto actual
                                </label>
                            @endif

                            @error('avatar')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="name" class="mb-2 block text-sm font-semibold text-ink">
                        Nome completo <span class="text-red-500">*</span>
                    </label>

                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Ex.: João Manuel"
                           autocomplete="name"
                           required
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                           focus:border-brand focus:ring-brand @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-ink">
                        Email
                    </label>

                    <input id="email"
                           name="email"
                           type="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="nome@exemplo.com"
                           autocomplete="email"
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                           focus:border-brand focus:ring-brand @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="mb-2 block text-sm font-semibold text-ink">
                        Telefone <span class="text-red-500">*</span>
                    </label>

                    <input id="phone"
                           name="phone"
                           type="tel"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="Ex.: 841234567"
                           autocomplete="tel"
                           required
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                           focus:border-brand focus:ring-brand @error('phone') border-red-500 @enderror">

                    @error('phone')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end border-t border-line p-5 sm:p-6">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                    Guardar alterações
                </button>
            </div>
        </div>
    </form>

    {{-- Erros do formulário de palavra-passe --}}
    @if ($passwordErrors->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/5 p-4 text-sm text-red-600">
            <div class="flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert preview-icon"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                Não foi possível alterar a palavra-passe.
            </div>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($passwordErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Palavra-passe --}}
    <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-key-round preview-icon"><path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/></svg>
                    </span>

                    <div>
                        <h2 class="font-bold text-ink">Alterar palavra-passe</h2>
                        <p class="mt-1 text-xs text-mute">Usa pelo menos 8 caracteres.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                <div class="sm:col-span-2">
                    <label for="current_password" class="mb-2 block text-sm font-semibold text-ink">
                        Palavra-passe actual <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <input id="current_password"
                               name="current_password"
                               type="password"
                               autocomplete="current-password"
                               required
                               class="w-full rounded-xl border-line bg-bg px-4 py-3 pr-12 text-sm text-ink placeholder:text-mute
                               focus:border-brand focus:ring-brand @if ($passwordErrors->has('current_password')) border-red-500 @endif">

                        <button type="button"
                                data-toggle-password="current_password"
                                aria-label="Mostrar palavra-passe"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-mute transition hover:text-ink">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>

                    @if ($passwordErrors->has('current_password'))
                        <p class="mt-1.5 text-xs text-red-500">{{ $passwordErrors->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-ink">
                        Nova palavra-passe <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <input id="password"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               required
                               class="w-full rounded-xl border-line bg-bg px-4 py-3 pr-12 text-sm text-ink placeholder:text-mute
                               focus:border-brand focus:ring-brand @if ($passwordErrors->has('password')) border-red-500 @endif">

                        <button type="button"
                                data-toggle-password="password"
                                aria-label="Mostrar palavra-passe"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-mute transition hover:text-ink">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>

                    @if ($passwordErrors->has('password'))
                        <p class="mt-1.5 text-xs text-red-500">{{ $passwordErrors->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-ink">
                        Confirmar nova palavra-passe <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               autocomplete="new-password"
                               required
                               class="w-full rounded-xl border-line bg-bg px-4 py-3 pr-12 text-sm text-ink placeholder:text-mute
                               focus:border-brand focus:ring-brand">

                        <button type="button"
                                data-toggle-password="password_confirmation"
                                aria-label="Mostrar palavra-passe"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-mute transition hover:text-ink">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="flex justify-end border-t border-line p-5 sm:p-6">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                    Alterar palavra-passe
                </button>
            </div>
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

    // Pré-visualização da foto de perfil
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    const initials = document.getElementById('avatar-initials');
    const removeBox = document.getElementById('remove-avatar');
    const originalSrc = preview ? preview.getAttribute('src') : '';

    const showImage = (src) => {
        preview.src = src;
        preview.classList.remove('hidden');
        initials.classList.add('hidden');
    };

    const showInitials = () => {
        preview.classList.add('hidden');
        initials.classList.remove('hidden');
    };

    if (avatarInput && preview && initials) {
        avatarInput.addEventListener('change', () => {
            const file = avatarInput.files[0];
            if (!file) return;

            if (removeBox) removeBox.checked = false;
            showImage(URL.createObjectURL(file));
        });
    }

    if (removeBox) {
        removeBox.addEventListener('change', () => {
            if (removeBox.checked) {
                avatarInput.value = '';
                showInitials();
            } else if (originalSrc) {
                showImage(originalSrc);
            }
        });
    }

    if (window.lucide) {
        window.lucide.createIcons();
    }
});
</script>
@endpush