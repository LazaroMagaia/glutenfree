
<x-modal
    id="delete-category-modal"
    title="Eliminar categoria"
    size="sm"
>
    <form id="delete-category-form" method="POST" action="#">
        @csrf
        @method('DELETE')
    </form>

    <div class="flex items-start gap-3">
        {{-- Ícone de alerta --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert preview-icon"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        </div>

        {{-- Mensagem de confirmação --}}
        <p class="text-sm leading-6 text-mute">
            Tens a certeza de que pretendes eliminar a categoria
            <strong
                id="delete-category-name"
                class="break-words text-ink"
            ></strong>?

            Esta ação não pode ser desfeita.
        </p>
    </div>

    <x-slot:footer>
        <button
            type="button"
            data-modal-close
            class="rounded-xl border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-soft"
        >
            Cancelar
        </button>

        <button
            type="submit"
            form="delete-category-form"
            id="delete-category-confirm"
            class="rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-70"
        >
            Eliminar
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('delete-category-modal');
    const form = document.getElementById('delete-category-form');
    const name = document.getElementById('delete-category-name');
    const confirm = document.getElementById('delete-category-confirm');

    if (!modal || !form || !name || !confirm) return;

    modal.addEventListener('modal:open', (event) => {
        const trigger = event.detail?.trigger;

        if (!trigger) return;

        form.action = trigger.dataset.deleteUrl || '#';
        name.textContent = trigger.dataset.deleteName || 'esta categoria';

        confirm.disabled = false;
        confirm.textContent = 'Eliminar';
    });

    form.addEventListener('submit', () => {
        confirm.disabled = true;
        confirm.textContent = 'A eliminar...';
    });
});
</script>
@endpush