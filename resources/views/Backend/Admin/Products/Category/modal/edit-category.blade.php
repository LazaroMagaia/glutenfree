<x-modal
    id="edit-category-modal"
    title="Editar categoria"
    size="lg"
>
    <form
        id="edit-category-form"
        method="POST"
        action="#"
        enctype="multipart/form-data"
        class="space-y-5"
    >
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div>
            <label for="edit-name" class="mb-2 block text-sm font-semibold text-ink">
                Nome da categoria <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                name="name"
                id="edit-name"
                required
                placeholder="Ex.: Eletrónicos"
                class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute outline-none transition 
                focus:border-brand focus:ring-2 focus:ring-brand @error('name') border-red-500 @enderror"
            >

            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Categoria pai --}}
        <div>
            <label for="edit-parent-id" class="mb-2 block text-sm font-semibold text-ink">
                Categoria pai
            </label>

            <select
                name="parent_id"
                id="edit-parent-id"
                class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink outline-none transition focus:border-brand 
                focus:ring-2 focus:ring-brand"
            >
                <option value="">Categoria principal</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        data-category-option
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
            <label for="edit-description" class="mb-2 block text-sm font-semibold text-ink">
                Descrição
            </label>

            <textarea
                name="description"
                id="edit-description"
                rows="4"
                placeholder="Descreve brevemente esta categoria..."
                class="w-full resize-y rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute outline-none
                transition focus:border-brand focus:ring-2 focus:ring-brand"
            ></textarea>

            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Imagem atual --}}
        <div id="edit-current-image-container" class="hidden">
            <label class="mb-2 block text-sm font-semibold text-ink">
                Imagem atual
            </label>

            <img
                id="edit-current-image"
                src=""
                alt="Imagem atual da categoria"
                class="h-40 w-full rounded-xl border border-line object-cover sm:w-64"
            >
        </div>

        {{-- Nova imagem --}}
        <div>
            <label for="edit-image" class="mb-2 block text-sm font-semibold text-ink">
                Alterar imagem
            </label>

            <input
                type="file"
                name="image"
                id="edit-image"
                accept="image/*"
                class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink file:mr-4 file:rounded-lg file:border-0 file:bg-soft 
                file:px-3 file:py-2 file:text-sm file:font-semibold file:text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand"
            >

            <p class="mt-1 text-xs text-mute">
                Deixa vazio para manter a imagem atual.
            </p>

            <div id="edit-image-preview-container" class="mt-3 hidden">
                <p class="mb-2 text-sm font-medium text-ink">
                    Pré-visualização
                </p>

                <img
                    id="edit-image-preview"
                    src=""
                    alt="Pré-visualização da nova imagem"
                    class="h-40 w-full rounded-xl border border-line object-cover sm:w-64"
                >
            </div>

            @error('image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ordem e estado --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="edit-sort-order" class="mb-2 block text-sm font-semibold text-ink">
                    Ordem de exibição
                </label>

                <input
                    type="number"
                    name="sort_order"
                    id="edit-sort-order"
                    min="0"
                    value="0"
                    class="w-full rounded-xl border border-line bg-bg px-4 py-3 text-sm text-ink outline-none transition focus:border-brand 
                    focus:ring-2 focus:ring-brand"
                >

                @error('sort_order')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-7">
                <input
                    type="checkbox"
                    name="is_active"
                    id="edit-is-active"
                    value="1"
                    checked
                    class="h-4 w-4 rounded border-line accent-primary"
                >

                <label for="edit-is-active" class="text-sm font-medium text-ink">
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
            form="edit-category-form"
            id="edit-category-submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm 
            transition hover:opacity-90"
        >
            Guardar alterações
        </button>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('edit-category-modal');
    const form = document.getElementById('edit-category-form');
    const submit = document.getElementById('edit-category-submit');

    const name = document.getElementById('edit-name');
    const parent = document.getElementById('edit-parent-id');
    const description = document.getElementById('edit-description');
    const image = document.getElementById('edit-image');
    const sortOrder = document.getElementById('edit-sort-order');
    const isActive = document.getElementById('edit-is-active');

    const currentImageContainer = document.getElementById('edit-current-image-container');
    const currentImage = document.getElementById('edit-current-image');

    const previewContainer = document.getElementById('edit-image-preview-container');
    const preview = document.getElementById('edit-image-preview');

    if (!modal || !form || !submit) return;

    modal.addEventListener('modal:open', (event) => {
        const trigger = event.detail?.trigger;

        if (!trigger) return;

        form.action = trigger.dataset.editUrl || '#';

        name.value = trigger.dataset.editName || '';
        description.value = trigger.dataset.editDescription || '';
        sortOrder.value = trigger.dataset.editSortOrder || '0';

        isActive.checked = trigger.dataset.editActive === '1';

        // Impedir que uma categoria seja a sua própria categoria pai.
        Array.from(parent.options).forEach(option => {
            option.disabled =
                option.value !== '' &&
                option.value === trigger.dataset.editId;
        });

        parent.value = trigger.dataset.editParent || '';

        // Imagem atual.
        const imageUrl = trigger.dataset.editImage || '';

        if (imageUrl) {
            currentImage.src = imageUrl;
            currentImageContainer.classList.remove('hidden');
        } else {
            currentImage.removeAttribute('src');
            currentImageContainer.classList.add('hidden');
        }

        // Limpar a seleção de ficheiro anterior.
        image.value = '';
        preview.removeAttribute('src');
        previewContainer.classList.add('hidden');

        submit.disabled = false;
        submit.textContent = 'Guardar alterações';
        submit.classList.remove('opacity-70', 'cursor-not-allowed');
    });

    // Pré-visualizar a nova imagem.
    image.addEventListener('change', () => {
        const file = image.files[0];

        preview.removeAttribute('src');
        previewContainer.classList.add('hidden');

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            image.value = '';
            return;
        }

        preview.src = URL.createObjectURL(file);
        previewContainer.classList.remove('hidden');
    });

    form.addEventListener('submit', () => {
        submit.disabled = true;
        submit.textContent = 'A guardar...';
        submit.classList.add('opacity-70', 'cursor-not-allowed');
    });
});
</script>
@endpush