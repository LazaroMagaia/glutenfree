@extends('layouts.backend')

@section('title', 'Gluten Free · Utilizadores')

@section('content')

<div id="toast-container" class="fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-2"></div>

<section class="space-y-6">

    @include('components.flash')
    @include('Backend.Admin.Users.Modals.delete')
    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm font-medium text-mute">
                Administração / Utilizadores
            </p>

            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Gestão de utilizadores
            </h1>

            <p class="mt-2 text-sm text-mute">
                Gere os administradores, clientes e as respetivas contas.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink
                    shadow-sm transition hover:opacity-90">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus preview-icon"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Novo utilizador
        </a>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Total de utilizadores</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users preview-icon"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $totalUsers ?? 0 }}</p>
            <p class="mt-1 text-xs text-mute">Contas registadas no sistema</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Administradores</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $totalAdmins ?? 0 }}</p>
            <p class="mt-1 text-xs text-mute">Com acesso administrativo</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Clientes</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $totalCustomers ?? 0 }}</p>
            <p class="mt-1 text-xs text-mute">Contas de clientes</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Novos utilizadores</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-plus preview-icon"><path d="M2 21a8 8 0 0 1 13.292-6"/><circle cx="10" cy="8" r="5"/><path d="M19 16v6"/><path d="M22 19h-6"/></svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $newUsers ?? 0 }}</p>
            <p class="mt-1 text-xs text-mute">Registados nos últimos 30 dias</p>
        </div>

    </div>

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">

        <div class="border-b border-line p-5">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                <div>
                    <h2 class="text-lg font-bold text-ink">Todos os utilizadores</h2>
                    <p class="mt-1 text-sm text-mute">
                        Pesquisa e gere as contas da plataforma.
                    </p>
                </div>

                <form action="{{ url()->current() }}"
                      method="GET"
                      class="flex flex-col gap-3 sm:flex-row">

                    <div class="relative">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search preview-icon pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-mute"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nome, email ou telefone..."
                            class="w-full rounded-xl border-line bg-bg py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-mute
                            focus:border-brand focus:ring-brand sm:w-64"
                        >
                    </div>

                    <select
                        name="role"
                        class="rounded-xl border-line bg-bg py-2.5 text-sm text-ink focus:border-brand focus:ring-brand"
                    >
                        <option value="">Todos os papéis</option>
                        <option value="admin" @selected(request('role') === 'admin')>
                            Administradores
                        </option>
                        <option value="customer" @selected(request('role') === 'user')>
                            Clientes
                        </option>
                    </select>

                    <button type="submit"
                            class="rounded-xl border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-soft">
                        Filtrar
                    </button>

                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ url()->current() }}"
                           class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold text-mute hover:bg-soft">
                            Limpar
                        </a>
                    @endif

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">

                <thead class="bg-soft/60 text-xs uppercase tracking-wider text-mute">
                    <tr>
                        <th class="px-5 py-4 font-bold">Utilizador</th>
                        <th class="px-5 py-4 font-bold">Telefone</th>
                        <th class="px-5 py-4 font-bold">Papel</th>
                        <th class="px-5 py-4 font-bold">Registado em</th>
                        <th class="px-5 py-4 text-right font-bold">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line">

                    @forelse($users ?? [] as $user)
                        @php
                            $isAdmin = $user->isAdmin();
                        @endphp

                        <tr class="transition hover:bg-soft/40">

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-soft font-bold text-ink">
                                        @if($user->avatar ?? false)
                                            <img src="{{ asset('storage/'.$user->avatar) }}"
                                                 alt="{{ $user->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>

                                    <div>
                                        <p class="font-bold text-ink">{{ $user->name }}</p>
                                        <p class="mt-0.5 text-xs text-mute">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-mute">
                                {{ $user->phone ?: '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold
                                    {{ $isAdmin ? 'bg-accent/10 text-accent' : 'bg-soft text-ink' }}">
                                    @if($isAdmin)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check preview-icon"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round preview-icon"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                                    @endif
                                    {{ $isAdmin ? 'Administrador' : 'Cliente' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-mute">
                                {{ $user->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.users.show', $user) }}"
                                       title="Ver utilizador"
                                       aria-label="Ver utilizador"
                                       class="rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye preview-icon"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>

                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       title="Editar utilizador"
                                       aria-label="Editar utilizador"
                                       class="rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen preview-icon"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                    </a>

                                    {{-- Só esconde o botão na própria conta --}}
                                    @if(auth()->id() !== $user->id)
                                        <button
                                            type="button"
                                            data-modal-open="delete-user-modal"
                                            data-delete-url="{{ route('admin.users.destroy', $user) }}"
                                            data-delete-name="{{ $user->name }}"
                                            class="rounded-lg p-2 text-red-500 transition hover:bg-red-500/10"
                                            aria-label="Eliminar utilizador {{ $user->name }}"
                                            class="rounded-lg p-2 text-red-500 transition hover:bg-red-500/10">
                                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                            class="lucide lucide-trash preview-icon"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-soft text-mute">
                                    <i data-lucide="users" class="h-7 w-7"></i>
                                </div>

                                <h3 class="mt-4 font-bold text-ink">
                                    Nenhum utilizador encontrado
                                </h3>

                                <p class="mt-1 text-sm text-mute">
                                    Tenta alterar os filtros ou cria um novo utilizador.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if(isset($users) && method_exists($users, 'links'))
            <div class="border-t border-line px-5 py-4">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif

    </div>

</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const deleteUrl = @json(route('admin.users.destroy', ':id'));
    const container = document.getElementById('toast-container');

    const styles = {
        success: 'border-green-500/30 bg-green-500/10 text-green-600',
        error: 'border-red-500/30 bg-red-500/10 text-red-500',
    };

    function toast(message, type = 'success') {
        const el = document.createElement('div');
        el.setAttribute('role', 'alert');
        el.className = `rounded-xl border bg-surface px-4 py-3 text-sm font-medium shadow-lg transition duration-300 ${styles[type]}`;
        el.textContent = message;
        container.appendChild(el);

        setTimeout(() => {
            el.classList.add('opacity-0', 'translate-x-4');
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }

    document.querySelectorAll('.delete-user').forEach((button) => {
        button.addEventListener('click', async () => {
            if (!confirm('Tens a certeza de que pretendes eliminar este utilizador?')) return;

            const row = button.closest('tr');
            button.disabled = true;
            button.classList.add('opacity-50');

            try {
                const response = await fetch(deleteUrl.replace(':id', button.dataset.userId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const messages = {
                        403: 'Não tens permissão para esta ação.',
                        404: 'Utilizador não encontrado.',
                        419: 'A sessão expirou. Recarrega a página.',
                    };
                    throw new Error(data.message || messages[response.status] || 'Ocorreu um erro inesperado.');
                }

                row.classList.add('opacity-0', 'transition', 'duration-300');
                setTimeout(() => row.remove(), 300);
                toast(data.message || 'Utilizador eliminado com sucesso.', 'success');

            } catch (error) {
                button.disabled = false;
                button.classList.remove('opacity-50');
                toast(error.message || 'Erro de ligação. Verifica a tua internet.', 'error');
            }
        });
    });

    if (window.lucide) lucide.createIcons();
});
</script>
@endpush