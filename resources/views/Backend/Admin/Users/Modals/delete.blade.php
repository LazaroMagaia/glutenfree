
<x-modal
    id="delete-user-modal"
    title="Eliminar utilizador"
    size="sm"
>
    <form id="delete-user-form" method="POST" action="#">
        @csrf
        @method('DELETE')
    </form>

    <div class="flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert preview-icon"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        </div>

        <p class="text-sm leading-6 text-mute">
            Tens a certeza de que pretendes eliminar
            <strong id="delete-user-name" class="text-ink"></strong>?
            Esta ação não pode ser desfeita
        </p>
    </div>

    <x-slot:footer>
        <button
            type="button"
            data-modal-close
            class="rounded-xl border border-line px-4 py-2.5 text-sm font-semibold text-ink hover:bg-soft"
        >
            Cancelar
        </button>

        <button
            type="submit"
            form="delete-user-form"
            id="delete-user-confirm"
            class="rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-600"
        >
            Eliminar
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('delete-user-modal');
    const form = document.getElementById('delete-user-form');
    const name = document.getElementById('delete-user-name');
    const confirm = document.getElementById('delete-user-confirm');

    if (!modal || !form || !name || !confirm) return;

    modal.addEventListener('modal:open', (event) => {
        const trigger = event.detail.trigger;

        if (!trigger) return;

        form.action = trigger.dataset.deleteUrl;
        name.textContent = trigger.dataset.deleteName || '';

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
