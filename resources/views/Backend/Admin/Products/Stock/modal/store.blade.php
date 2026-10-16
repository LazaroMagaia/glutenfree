<x-modal
    id="create-stock-movement-modal"
    title="Registar movimento de stock"
    size="lg"
>
    
    <form
        id="create-stock-movement-form"
        method="POST"
        action="{{ route('admin.products.stock.store', $routeParams) }}"
        class="space-y-5"
    >
        @csrf

        <input
            type="hidden"
            name="idempotency_key"
            value="{{ old('idempotency_key', $idempotencyKey) }}"
        >

        {{-- Tipo de movimento --}}
        <div>
            <label for="stock-movement-type" class="mb-2 block text-sm font-semibold text-ink">
                Tipo de movimento <span class="text-red-500">*</span>
            </label>

            <select
                name="type"
                id="stock-movement-type"
                required
                class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink focus:border-brand focus:ring-brand @error('type') border-red-500 @enderror"
            >
                <option value="">Seleciona o tipo</option>

             @foreach($manualTypes as $movementType)
                <option
                    value="{{ $movementType->value }}"
                    data-requires-reason="{{ $movementType->requiresReason() ? '1' : '0' }}"
                    data-is-adjustment="{{ $movementType->value === 'adjustment' ? '1' : '0' }}"
                    @selected(old('type') === $movementType->value)
                >
                    {{ $movementType->label() }}
                </option>
            @endforeach
            </select>

            @error('type')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Quantidade --}}
        <div>
            <label for="stock-movement-quantity" class="mb-2 block text-sm font-semibold text-ink">
                Quantidade <span class="text-red-500">*</span>
            </label>

            <input
                type="number"
                name="quantity"
                id="stock-movement-quantity"
                min="1"
                max="1000000"
                step="1"
                value="{{ old('quantity') }}"
                required
                placeholder="Ex.: 10"
                class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink focus:border-brand focus:ring-brand @error('quantity') border-red-500 @enderror"
            >

            <p class="mt-1 text-xs text-mute">
                Para um ajuste de stock, a quantidade pode ser zero.
            </p>

            @error('quantity')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Motivo --}}
        <div>
            <label for="stock-movement-reason" class="mb-2 block text-sm font-semibold text-ink">
                Motivo <span id="stock-movement-reason-required" class="text-red-500 hidden">*</span>
            </label>

            <textarea
                name="reason"
                id="stock-movement-reason"
                rows="3"
                maxlength="500"
                placeholder="Explica o motivo do movimento..."
                class="w-full resize-y rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand @error('reason') border-red-500 @enderror"
            >{{ old('reason') }}</textarea>

            @error('reason')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </form>

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
            form="create-stock-movement-form"
            id="create-stock-movement-submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90"
        >
            Registar movimento
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-stock-movement-form');
    const submit = document.getElementById('create-stock-movement-submit');
    const typeSelect = document.getElementById('stock-movement-type');
    const quantityInput = document.getElementById('stock-movement-quantity');
    const reasonInput = document.getElementById('stock-movement-reason');
    const reasonRequired = document.getElementById('stock-movement-reason-required');
    const modalTrigger = document.querySelector(
        '[data-modal-open="create-stock-movement-modal"]'
    );

    if (!form || !submit || !typeSelect || !quantityInput || !reasonInput) {
        return;
    }

    function updateFields() {
        const selected = typeSelect.options[typeSelect.selectedIndex];
        const isAdjustment = selected?.dataset.isAdjustment === '1';
        const requiresReason = selected?.dataset.requiresReason === '1';

        quantityInput.min = isAdjustment ? '0' : '1';

        reasonInput.required = requiresReason;
        reasonRequired.classList.toggle('hidden', !requiresReason);
    }

    typeSelect.addEventListener('change', updateFields);
    updateFields();

    form.addEventListener('submit', () => {
        submit.disabled = true;
        submit.textContent = 'A registar...';
        submit.classList.add('opacity-70', 'cursor-not-allowed');
    });

    @if($errors->any())
        modalTrigger?.click();
    @endif
});
</script>
@endpush