<x-modal
    id="create-category-modal"
    title="Criar categoria"
    size="lg"
>
    <form
        id="create-category-form"
        method="POST"
        action="{{ route('admin.products.categories.store') }}"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf

        {{-- Nome --}}
        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-ink">
                Nome da categoria <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                placeholder="Ex.: Eletrónicos"
                required
                class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand 
                       focus:ring-brand @error('name') border-red-500 @enderror"
            >

            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Categoria pai --}}
        <div>
            <label for="parent_id" class="mb-2 block text-sm font-semibold text-ink">
                Categoria pai
            </label>

            <select
                name="parent_id"
                id="parent_id"
                class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand 
                           focus:ring-brand @error('parent_id') border-red-500 @enderror"
            >
                <option value="">Categoria principal</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('parent_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('parent_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descrição --}}
        <div>
            <label for="description" class="mb-2 block text-sm font-semibold text-ink">
                Descrição
            </label>

            <textarea
                name="description"
                id="description"
                rows="4"
                placeholder="Descreve brevemente esta categoria..."
                class="w-full resize-y rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand 
                focus:outline-none focus:ring-2 focus:ring-brand @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Imagem --}}
        <div>
            <label for="image" class="mb-2 block text-sm font-semibold text-ink">
                Imagem da categoria
            </label>

            <input
                type="file"
                name="image"
                id="image"
                accept="image/*"
                class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink
                    file:mr-4 file:rounded-lg file:border-0
                    file:bg-soft file:px-3 file:py-2
                    file:text-sm file:font-semibold file:text-ink
                    focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand
                    @error('image') border-red-500 @enderror"
            >

            @error('image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ordem e estado --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-ink">
                    Ordem de exibição
                </label>

                <input
                    type="number"
                    name="sort_order"
                    id="sort_order"
                    min="0"
                    value="{{ old('sort_order', 0) }}"
                    class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink
                        placeholder:text-mute outline-none transition
                        focus:border-brand focus:ring-2 focus:ring-brand
                        @error('sort_order') border-red-500 @enderror"
                >

                @error('sort_order')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-3 pt-7">
                <input
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    @checked(old('is_active', true))
                    class="h-4 w-4 rounded border-line accent-primary"
                >

                <label for="is_active" class="text-sm font-medium text-ink">
                    Categoria ativa
                </label>
            </div>
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
            form="create-category-form"
            id="create-category-submit"
          class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink
                    shadow-sm transition hover:opacity-90"
        >
            Criar categoria
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-category-form');
    const submit = document.getElementById('create-category-submit');

    if (!form || !submit) return;

    form.addEventListener('submit', () => {
        submit.disabled = true;
        submit.textContent = 'A criar...';
        submit.classList.add('opacity-70', 'cursor-not-allowed');
    });
});
</script>
@endpush
