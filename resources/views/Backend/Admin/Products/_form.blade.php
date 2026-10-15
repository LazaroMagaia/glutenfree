@php
    use App\Enums\ProductStatus;
    use Illuminate\Support\Facades\Storage;

    $isEdit = isset($product) && $product !== null;
    $category = $category ?? $product?->category;

    $currentStatus = old('status', $product?->status ?? ProductStatus::Published);
    $currentStatus = $currentStatus instanceof \BackedEnum
        ? $currentStatus->value
        : $currentStatus;

    $nutrition = old('nutrition', $product?->nutrition ?? []);
    $nutrition = is_array($nutrition) ? $nutrition : [];

    $inputClass = 'w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink focus:border-brand focus:ring-brand';

    $fileClass = 'block w-full rounded-xl border-line bg-bg text-sm text-ink focus:border-brand focus:ring-brand file:mr-4
            file:rounded-lg file:border-0 file:bg-brand/10 file:px-4 file:py-2 file:font-medium file:text-brand hover:file:bg-brand/20';


    $stockDefaults = [
        'track_stock' => true,
        'quantity_on_hand' => 0,
        'low_stock_threshold' => 0,
        'quantity_reserved' => 0,
    ];

    $stock = [];
    foreach ($stockDefaults as $field => $default) {
        $stock[$field] = old($field, $product?->{$field} ?? $default);
    }
@endphp

{{-- Erros de validação --}}
@if ($errors->any())
    <div class="rounded-2xl border border-red-500/20 bg-red-500/5 p-4 sm:p-5">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 8v4m0 4h.01"/>
            </svg>

            <div class="min-w-0">
                <p class="font-semibold text-red-500">
                    Verifica os campos do formulário.
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-500">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Informações principais --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Informações principais</h2>
        <p class="mt-1 text-sm text-mute">
            Nome, referência, categoria e descrição do produto.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">

        <div class="col-span-12 sm:col-span-8">
            <label for="name" class="mb-2 block text-sm font-medium text-ink">
                Nome do produto <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   id="name"
                   name="name"
                   autocomplete="off"
                   value="{{ old('name', $product?->name) }}"
                   required
                   maxlength="255"
                   placeholder="Ex.: Pão integral sem glúten"
                   class="{{ $inputClass }}">

            @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-4">
            <label for="sku" class="mb-2 block text-sm font-medium text-ink">
                SKU <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   id="sku"
                   name="sku"
                   autocomplete="off"
                   value="{{ old('sku', $product?->sku) }}"
                   required
                   maxlength="64"
                   placeholder="Ex.: PAO-INT-001"
                   class="{{ $inputClass }}">

            @error('sku')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label for="category_name" class="mb-2 block text-sm font-medium text-ink">
                Categoria
            </label>

            <div class="flex min-w-0 items-center gap-3 rounded-xl border border-line bg-surface px-4 py-3">
                <svg class="h-5 w-5 shrink-0 text-mute"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7h7l2 2h9v10H3z"/>
                    <path d="M3 7V5h7l2 2"/>
                </svg>

                <div class="min-w-0 flex-1">
                    <p id="category_name" class="truncate text-sm font-medium text-ink">
                        {{ $category?->parent
                            ? $category->parent->name . ' / ' . $category->name
                            : ($category?->name ?? 'Categoria não definida') }}
                    </p>
                    <p class="mt-0.5 text-xs text-mute">
                        Categoria fixa — não pode ser alterada nesta página.
                    </p>
                </div>

                <svg class="h-4 w-4 shrink-0 text-mute"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="11" width="14" height="10" rx="2"/>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                </svg>
            </div>

            @if ($category)
                <input type="hidden" name="category_slug" value="{{ $category->slug }}">
            @endif

            @error('category_slug')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label for="short_description" class="mb-2 block text-sm font-medium text-ink">
                Descrição curta
            </label>

            <textarea id="short_description"
                      name="short_description"
                      rows="2"
                      placeholder="Uma breve apresentação do produto..."
                      class="{{ $inputClass }}">{{ old('short_description', $product?->short_description) }}</textarea>

            @error('short_description')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label for="description" class="mb-2 block text-sm font-medium text-ink">
                Descrição completa
            </label>

            <textarea id="description"
                      name="description"
                      rows="5"
                      placeholder="Descreve o produto, as suas características e benefícios..."
                      class="{{ $inputClass }}">{{ old('description', $product?->description) }}</textarea>

            @error('description')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

{{-- Imagens do produto --}}
<section
    class="overflow-hidden rounded-2xl border border-line bg-surface"
    x-data="productImagesForm({
        primaryUrl: @js(
            $isEdit && $product->primaryImage
                ? Storage::disk('public')->url($product->primaryImage->path)
                : null
        ),
        gallery: @js(
            $isEdit
                ? $product->images
                    ->where('is_primary', false)
                    ->map(fn ($image) => [
                        'id' => $image->id,
                        'url' => Storage::disk('public')->url($image->path),
                        'alt' => $image->alt,
                    ])->values()
                : []
        )
    })"
>
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Imagens do produto</h2>
        <p class="mt-1 text-sm text-mute">
            Define a imagem principal e adiciona fotografias complementares.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-6 p-5 sm:p-6">

        {{-- Imagem principal --}}
        <div class="col-span-12">
            <label for="primary_image" class="mb-2 block text-sm font-medium text-ink">
                Imagem principal
                @unless ($isEdit)
                    <span class="text-red-500">*</span>
                @endunless
            </label>

            <div class="grid grid-cols-12 items-start gap-5">
                <div class="col-span-12 sm:col-span-5 lg:col-span-4">
                    <div class="flex h-48 w-full items-center justify-center overflow-hidden rounded-xl border border-line bg-surface">
                        <template x-if="primaryPreview || primaryUrl">
                            <img :src="primaryPreview || primaryUrl"
                                 alt="Pré-visualização da imagem principal"
                                 class="h-full w-full object-contain">
                        </template>

                        <template x-if="!primaryPreview && !primaryUrl">
                            <div class="flex flex-col items-center gap-2 text-mute">
                                <svg class="h-10 w-10"
                                     viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <path d="m21 15-5-5L5 21"/>
                                </svg>
                                <span class="text-xs">Sem imagem selecionada</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="col-span-12 min-w-0 sm:col-span-7 lg:col-span-8">
                    <input type="file"
                           id="primary_image"
                           name="primary_image"
                           accept="image/jpeg,image/png,image/webp"
                           @change="previewPrimary($event)"
                           @if (!$isEdit) required @endif
                           class="{{ $fileClass }}">

                    <p class="mt-2 text-xs leading-5 text-mute">
                        Formatos: JPG, PNG ou WEBP. Tamanho máximo: 5 MB.
                        @if ($isEdit)
                            Deixa vazio para manter a imagem atual.
                        @endif
                    </p>

                    @error('primary_image')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-span-12 border-t border-line"></div>

        {{-- Galeria --}}
        <div class="col-span-12 min-w-0">
            <label for="images" class="block text-sm font-medium text-ink">
                Imagens adicionais
            </label>

            <p class="mt-1 text-xs text-mute">
                Podes selecionar várias imagens de uma só vez.
            </p>

            <input type="file"
                   id="images"
                   name="images[]"
                   accept="image/jpeg,image/png,image/webp"
                   multiple
                   @change="previewGallery($event)"
                   class="{{ $fileClass }} mt-3">

            @error('images')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror

            @error('images.*')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror

            {{-- Imagens existentes --}}
            <template x-if="gallery.length">
                <div class="mt-5">
                    <p class="mb-3 text-sm font-medium text-ink">
                        Imagens existentes
                    </p>

                    <div class="grid grid-cols-12 gap-4">
                        <template x-for="image in gallery" :key="image.id">
                            <div class="col-span-6 overflow-hidden rounded-xl border border-line sm:col-span-4 lg:col-span-3">
                                <img :src="image.url"
                                     :alt="image.alt || 'Imagem do produto'"
                                     class="h-32 w-full bg-surface object-cover">

                                <div class="p-2">
                                    <span class="text-xs text-mute">Imagem guardada</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Novas imagens --}}
            <template x-if="newGallery.length">
                <div class="mt-5">
                    <p class="mb-3 text-sm font-medium text-ink">
                        Novas imagens selecionadas
                    </p>

                    <div class="grid grid-cols-12 gap-4">
                        <template x-for="(image, index) in newGallery" :key="image.url">
                            <div class="col-span-6 overflow-hidden rounded-xl border border-line sm:col-span-4 lg:col-span-3">
                                <img :src="image.url"
                                     :alt="image.name"
                                     class="h-32 w-full bg-surface object-cover">

                                <div class="flex items-center justify-between gap-2 p-2">
                                    <span class="truncate text-xs text-mute" x-text="image.name"></span>

                                    <button type="button"
                                            @click="removeNewImage(index)"
                                            class="shrink-0 text-xs font-medium text-red-500 hover:text-red-600">
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</section>

{{-- Stock --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Gestão de stock</h2>
        <p class="mt-1 text-sm text-mute">
            Configura o controlo de stock, as quantidades e o alerta de stock baixo.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">

        <div class="col-span-12">
            <label for="track_stock"
                   class="flex cursor-pointer items-start gap-3 rounded-xl border border-line p-4 transition hover:bg-surface">
                <input type="hidden" name="track_stock" value="0">

                <input type="checkbox"
                       id="track_stock"
                       name="track_stock"
                       value="1"
                       @checked((bool) $stock['track_stock'])
                       class="mt-1 h-4 w-4 rounded border-line text-primary focus:ring-primary">

                <span>
                    <span class="block text-sm font-semibold text-ink">
                        Controlar stock deste produto
                    </span>
                    <span class="mt-1 block text-xs leading-5 text-mute">
                        Quando desativado, a aplicação não deve bloquear vendas por falta de stock.
                    </span>
                </span>
            </label>

            @error('track_stock')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6 lg:col-span-4">
            <label for="quantity_on_hand" class="mb-2 block text-sm font-medium text-ink">
                Quantidade em stock <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   id="quantity_on_hand"
                   name="quantity_on_hand"
                   min="0"
                   step="1"
                   required
                   value="{{ $stock['quantity_on_hand'] }}"
                   class="{{ $inputClass }}">

            <p class="mt-1 text-xs text-mute">
                Inclui as unidades reservadas.
            </p>

            @error('quantity_on_hand')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6 lg:col-span-4">
            <label for="quantity_reserved" class="mb-2 block text-sm font-medium text-ink">
                Quantidade reservada <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   id="quantity_reserved"
                   name="quantity_reserved"
                   min="0"
                   max="{{ $stock['quantity_on_hand'] }}"
                   step="1"
                   required
                   value="{{ $stock['quantity_reserved'] }}"
                   class="{{ $inputClass }}">

            <p class="mt-1 text-xs text-mute">
                Unidades comprometidas com encomendas abertas.
            </p>

            @error('quantity_reserved')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6 lg:col-span-4">
            <label for="low_stock_threshold" class="mb-2 block text-sm font-medium text-ink">
                Limite de stock baixo <span class="text-red-500">*</span>
            </label>

            <input type="number"
                   id="low_stock_threshold"
                   name="low_stock_threshold"
                   min="0"
                   step="1"
                   required
                   value="{{ $stock['low_stock_threshold'] }}"
                   class="{{ $inputClass }}">

            <p class="mt-1 text-xs text-mute">
                Usa 0 para desativar o alerta.
            </p>

            @error('low_stock_threshold')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <div class="rounded-xl border border-line p-4">
                <p class="text-sm font-medium text-ink">Stock disponível para venda</p>

                <p class="mt-1 text-2xl font-semibold text-ink"
                   x-data="{
                       onHand: {{ (int) $stock['quantity_on_hand'] }},
                       reserved: {{ (int) $stock['quantity_reserved'] }}
                   }"
                   x-text="Math.max(0, onHand - reserved)">
                    {{ max(0, (int) $stock['quantity_on_hand'] - (int) $stock['quantity_reserved']) }}
                </p>

                <p class="mt-1 text-xs text-mute">
                    Calculado pela quantidade em stock menos a quantidade reservada.
                    Este valor é apenas uma pré-visualização do formulário.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Ingredientes e segurança alimentar --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Ingredientes e segurança alimentar</h2>
        <p class="mt-1 text-sm text-mute">
            Informação importante para pessoas com restrições alimentares.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">
        <div class="col-span-12">
            <label for="ingredients" class="mb-2 block text-sm font-medium text-ink">
                Ingredientes
            </label>

            <textarea id="ingredients"
                      name="ingredients"
                      rows="3"
                      placeholder="Lista de ingredientes do produto..."
                      class="{{ $inputClass }}">{{ old('ingredients', $product?->ingredients) }}</textarea>

            @error('ingredients')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label for="allergens" class="mb-2 block text-sm font-medium text-ink">
                Alergénios
            </label>

            <textarea id="allergens"
                      name="allergens"
                      rows="3"
                      placeholder="Ex.: leite, soja, frutos de casca rija..."
                      class="{{ $inputClass }}">{{ old('allergens', $product?->allergens) }}</textarea>

            @error('allergens')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label for="cross_contamination_notice"
                   class="mb-2 block text-sm font-medium text-ink">
                Aviso de contaminação cruzada
            </label>

            <textarea id="cross_contamination_notice"
                      name="cross_contamination_notice"
                      rows="3"
                      placeholder="Indica possíveis riscos de contaminação cruzada..."
                      class="{{ $inputClass }}">{{ old('cross_contamination_notice', $product?->cross_contamination_notice) }}</textarea>

            @error('cross_contamination_notice')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12">
            <label for="gluten_free_certification"
                   class="mb-2 block text-sm font-medium text-ink">
                Certificação sem glúten
            </label>

            <input type="text"
                   id="gluten_free_certification"
                   name="gluten_free_certification"
                   autocomplete="off"
                   value="{{ old('gluten_free_certification', $product?->gluten_free_certification) }}"
                   maxlength="255"
                   placeholder="Entidade certificadora ou referência da certificação"
                   class="{{ $inputClass }}">

            @error('gluten_free_certification')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

{{-- Nutrição --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Informação nutricional</h2>
        <p class="mt-1 text-sm text-mute">
            Preenche os valores disponíveis no rótulo. Todos os campos são opcionais.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">
        @foreach ([
            'energy' => 'Energia (kcal)',
            'protein' => 'Proteínas (g)',
            'carbohydrates' => 'Hidratos de carbono (g)',
            'sugars' => 'Açúcares (g)',
            'fat' => 'Gorduras totais (g)',
            'saturated_fat' => 'Gorduras saturadas (g)',
            'fiber' => 'Fibra (g)',
            'salt' => 'Sal (g)',
            'sodium' => 'Sódio (mg)',
        ] as $key => $label)
            <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                <label for="nutrition_{{ $key }}"
                       class="mb-2 block text-sm font-medium text-ink">
                    {{ $label }}
                </label>

                <input type="number"
                       step="any"
                       min="0"
                       id="nutrition_{{ $key }}"
                       name="nutrition[{{ $key }}]"
                       value="{{ $nutrition[$key] ?? '' }}"
                       placeholder="0"
                       class="{{ $inputClass }}">

                @error("nutrition.$key")
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</section>

{{-- Conservação e preparação --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Conservação e preparação</h2>
        <p class="mt-1 text-sm text-mute">
            Orientações para armazenamento e utilização do produto.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">
        <div class="col-span-12 sm:col-span-6">
            <label for="storage_info" class="mb-2 block text-sm font-medium text-ink">
                Conservação
            </label>

            <textarea id="storage_info"
                      name="storage_info"
                      rows="4"
                      placeholder="Ex.: conservar num local fresco e seco..."
                      class="{{ $inputClass }}">{{ old('storage_info', $product?->storage_info) }}</textarea>

            @error('storage_info')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="col-span-12 sm:col-span-6">
            <label for="preparation_info" class="mb-2 block text-sm font-medium text-ink">
                Preparação
            </label>

            <textarea id="preparation_info"
                      name="preparation_info"
                      rows="4"
                      placeholder="Ex.: instruções de preparação ou utilização..."
                      class="{{ $inputClass }}">{{ old('preparation_info', $product?->preparation_info) }}</textarea>

            @error('preparation_info')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

{{-- Estado e características --}}
<section class="overflow-hidden rounded-2xl border border-line bg-surface">
    <div class="border-b border-line px-5 py-4 sm:px-6">
        <h2 class="font-semibold text-ink">Estado e características</h2>
        <p class="mt-1 text-sm text-mute">
            Define a publicação e as características do produto.
        </p>
    </div>

    <div class="grid grid-cols-12 gap-5 p-5 sm:p-6">
        <div class="col-span-12 sm:col-span-6 lg:col-span-4">
            <label for="status" class="mb-2 block text-sm font-medium text-ink">
                Estado <span class="text-red-500">*</span>
            </label>

            <select id="status"
                    name="status"
                    required
                    class="{{ $inputClass }}">
                @foreach (ProductStatus::cases() as $status)
                    <option value="{{ $status->value }}"
                            @selected($currentStatus === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            @error('status')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        @foreach ([
            'is_featured' => [
                'label' => 'Produto em destaque',
                'description' => 'Dar maior visibilidade ao produto.',
            ],
            'is_new' => [
                'label' => 'Produto novo',
                'description' => 'Identificar o produto como uma novidade.',
            ],
            'is_favorite' => [
                'label' => 'Produto favorito',
                'description' => 'Marcar o produto como favorito.',
            ],
            'is_purchasable' => [
                'label' => 'Disponível para compra',
                'description' => 'Indicar que o produto pode ser comprado.',
            ],
        ] as $field => $item)
            <div class="col-span-12 sm:col-span-6">
                <label for="{{ $field }}"
                       class="flex h-full cursor-pointer items-start gap-3 rounded-xl border border-line p-4 transition hover:bg-surface">
                    <input type="hidden" name="{{ $field }}" value="0">

                    <input type="checkbox"
                           id="{{ $field }}"
                           name="{{ $field }}"
                           value="1"
                           @checked((bool) old($field, $product?->{$field} ?? false))
                           class="mt-1 h-4 w-4 shrink-0 rounded border-line text-primary focus:ring-primary">

                    <span>
                        <span class="block text-sm font-semibold text-ink">
                            {{ $item['label'] }}
                        </span>
                        <span class="mt-1 block text-xs leading-5 text-mute">
                            {{ $item['description'] }}
                        </span>
                    </span>
                </label>
            </div>
        @endforeach
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('productImagesForm', (config = {}) => ({
        primaryUrl: config.primaryUrl || null,
        primaryPreview: null,
        gallery: config.gallery || [],
        newGallery: [],

        previewPrimary(event) {
            const file = event.target.files[0];

            if (this.primaryPreview) {
                URL.revokeObjectURL(this.primaryPreview);
            }

            this.primaryPreview = file
                ? URL.createObjectURL(file)
                : null;
        },

        previewGallery(event) {
            this.newGallery.forEach(image => URL.revokeObjectURL(image.url));

            this.newGallery = Array.from(event.target.files).map(file => ({
                name: file.name,
                url: URL.createObjectURL(file),
            }));
        },

        removeNewImage(index) {
            const input = document.getElementById('images');
            const files = Array.from(input.files);

            URL.revokeObjectURL(this.newGallery[index].url);

            files.splice(index, 1);
            this.newGallery.splice(index, 1);

            const transfer = new DataTransfer();
            files.forEach(file => transfer.items.add(file));

            input.files = transfer.files;
        },

        destroy() {
            if (this.primaryPreview) {
                URL.revokeObjectURL(this.primaryPreview);
            }

            this.newGallery.forEach(image => URL.revokeObjectURL(image.url));
        },
    }));
});
</script>