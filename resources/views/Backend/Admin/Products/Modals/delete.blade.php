
<x-modal
    id="delete-product-modal"
    title="Eliminar produto"
    size="sm"
>
    <form id="delete-product-form" method="POST" action="#">
        @csrf
        @method('DELETE')
    </form>

    <div class="flex items-start gap-3">

        {{-- Ícone de alerta --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="24"
                 height="24"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/>
                <path d="M12 9v4"/>
                <path d="M12 17h.01"/>
            </svg>
        </div>

        {{-- Mensagem de confirmação --}}
        <p class="text-sm leading-6 text-mute">
            Tens a certeza de que pretendes eliminar o produto
            <strong
                id="delete-product-name"
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
            form="delete-product-form"
            id="delete-product-confirm"
            class="rounded-xl bg-red-500 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-70"
        >
            Eliminar
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('delete-product-modal');
    const form = document.getElementById('delete-product-form');
    const nameEl = document.getElementById('delete-product-name');
    const confirmBtn = document.getElementById('delete-product-confirm');

    if (!modal || !form || !nameEl || !confirmBtn) return;

    const resetButton = () => {
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Eliminar';
    };

    modal.addEventListener('modal:open', (event) => {
        const trigger = event.detail?.trigger;
        if (!trigger) return;

        form.action = trigger.dataset.deleteUrl || '#';
        nameEl.textContent = trigger.dataset.deleteName || 'este produto';
        resetButton();
    });

    form.addEventListener('submit', (event) => {
        // Evita submeter para "#" se o URL não foi definido.
        if (form.getAttribute('action') === '#') {
            event.preventDefault();
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'A eliminar...';
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) resetButton();
    });
});
</script>
@endpush