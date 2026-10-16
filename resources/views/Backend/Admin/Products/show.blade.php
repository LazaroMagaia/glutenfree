 @extends('layouts.backend')

@section('title', 'Gluten Free · Detalhes do produto')

@section('content')
@php
    $status = $product->status instanceof \BackedEnum
        ? $product->status->value
        : $product->status;

    $stockStatus = $product->stock_status;

    $available = $product->available;

    $money = fn ($value) => number_format((float) $value, 2, ',', '.') . ' MT';

    $stockLabels = [
        'untracked' => 'Sem controlo de stock',
        'out' => 'Sem stock',
        'low' => 'Stock baixo',
        'ok' => 'Stock disponível',
    ];

    $stockColors = [
        'untracked' => 'bg-surface text-mute border-line',
        'out' => 'bg-red-50 text-red-700 border-red-200',
        'low' => 'bg-amber-50 text-amber-700 border-amber-200',
        'ok' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ];

    $statusColors = [
        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
        'inactive' => 'bg-red-50 text-red-700 border-red-200',
    ];
@endphp

<div class="mx-auto max-w-7xl space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ url()->previous() }}"
               class="mb-2 inline-flex items-center gap-2 text-sm text-mute transition hover:text-ink">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Voltar
            </a>

            <h1 class="text-2xl font-bold tracking-tight text-ink">
                Detalhes do produto
            </h1>

            <p class="mt-1 text-sm text-mute">
                Consulta todas as informações, preços e disponibilidade do produto.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-semibold {{ $stockColors[$stockStatus] ?? $stockColors['untracked'] }}">
                {{ $stockLabels[$stockStatus] ?? 'Estado desconhecido' }}
            </span>

            <a href="#"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                </svg>
                Editar produto
            </a>
        </div>
    </div>

    @include('components.flash')

    {{-- Resumo --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-line bg-surface p-5">
            <p class="text-sm text-mute">Preço normal</p>
            <p class="mt-2 text-2xl font-bold text-ink">
                {{ $money($product->price) }}
            </p>
            @if($product->sale_price !== null)
                <p class="mt-1 text-xs font-medium text-emerald-600">
                    Promoção: {{ $money($product->sale_price) }}
                </p>
            @endif
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <p class="text-sm text-mute">Stock físico</p>
            <p class="mt-2 text-2xl font-bold text-ink">
                {{ number_format($product->quantity_on_hand) }}
            </p>
            <p class="mt-1 text-xs text-mute">Unidades registadas</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <p class="text-sm text-mute">Stock disponível</p>
            <p class="mt-2 text-2xl font-bold text-ink">
                {{ number_format($available) }}
            </p>
            <p class="mt-1 text-xs text-mute">Após reservas e indisponibilidades</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <p class="text-sm text-mute">Peso</p>
            <p class="mt-2 text-2xl font-bold text-ink">
                {{ $product->weight_grams !== null
                    ? number_format($product->weight_grams) . ' g'
                    : '—' }}
            </p>
            <p class="mt-1 text-xs text-mute">Peso do produto</p>
        </div>

    </div>

    {{-- Informação principal --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Imagens --}}
        <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
            <h2 class="text-base font-bold text-ink">Imagens do produto</h2>
            <p class="mt-1 text-sm text-mute">Imagem principal e restantes imagens.</p>

            @php
                $primaryImage = $product->images->firstWhere('is_primary', true)
                    ?? $product->images->first();
            @endphp

            @if($primaryImage)
                <div class="mt-5 overflow-hidden rounded-2xl border border-line bg-white">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($primaryImage->path) }}"
                        alt="{{ $primaryImage->alt ?: $product->name }}"
                        class="h-48 w-full object-contain"
                    >
                </div>
            @else
                <div class="mt-5 flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-line bg-surface text-mute">
                    <svg class="h-12 w-12" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="m21 15-5-5L5 21"/>
                    </svg>
                    <p class="mt-3 text-sm">Sem imagens registadas</p>
                </div>
            @endif

            @if($product->images->count() > 1)
                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach($product->images as $image)
                        <div class="relative overflow-hidden rounded-xl border {{ $image->is_primary ? 'border-brand ring-2 ring-brand/20' : 'border-line' }}">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($image->path) }}"
                                alt="{{ $image->alt ?: $product->name }}"
                                class="h-30 w-full object-cover"
                            >
                            @if($image->is_primary)
                                <span class="absolute inset-x-1 bottom-1 rounded-md bg-white/95 px-1 py-1 text-center text-[10px] font-bold text-ink">
                                    Principal
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Dados gerais --}}
        <section class="space-y-6 lg:col-span-2">

            <div class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-mute">
                            Produto #{{ $product->id }}
                        </p>
                        <h2 class="mt-2 text-xl font-bold text-ink">
                            {{ $product->name }}
                        </h2>
                        <p class="mt-1 text-sm text-mute">
                            {{ $product->short_description ?: 'Sem descrição curta.' }}
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusColors[$status] ?? 'border-line bg-surface text-mute' }}">
                        {{ ucfirst(str_replace('_', ' ', $status ?? 'sem estado')) }}
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-medium text-mute">Categoria</p>
                        <p class="mt-1 text-sm font-semibold text-ink">
                            {{ $product->category?->name ?? 'Sem categoria' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-mute">SKU</p>
                        <p class="mt-1 text-sm font-semibold text-ink">
                            {{ $product->sku ?: '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-mute">Slug</p>
                        <p class="mt-1 break-all text-sm text-ink">
                            {{ $product->slug }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-mute">Criado em</p>
                        <p class="mt-1 text-sm text-ink">
                            {{ $product->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-mute">Última atualização</p>
                        <p class="mt-1 text-sm text-ink">
                            {{ $product->updated_at?->format('d/m/Y H:i') ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-mute">Estado de compra</p>
                        <p class="mt-1 text-sm font-semibold {{ $product->is_purchasable ? 'text-emerald-600' : 'text-mute' }}">
                            {{ $product->is_purchasable ? 'Pode ser comprado' : 'Compra desativada' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Configurações --}}
            <div class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
                <h2 class="text-base font-bold text-ink">Configurações do produto</h2>

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach([
                        'is_featured' => 'Produto em destaque',
                        'is_new' => 'Produto novo',
                        'is_favorite' => 'Produto favorito',
                        'is_purchasable' => 'Disponível para compra',
                        'gluten_free_certification' => 'Certificação sem glúten',
                        'track_stock' => 'Controlo de stock',
                    ] as $field => $label)
                        @php
                            $enabled = $field === 'gluten_free_certification'
                                ? (bool) $product->{$field}
                                : (bool) $product->{$field};
                        @endphp

                        <div class="flex items-center justify-between gap-3 rounded-xl border border-line p-3">
                            <span class="text-sm text-ink">{{ $label }}</span>

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $enabled ? 'bg-emerald-50 text-emerald-700' : 'bg-surface text-mute' }}">
                                {{ $enabled ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    {{-- Descrição --}}
    <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
        <h2 class="text-base font-bold text-ink">Descrição</h2>

        <div class="mt-4">
            <p class="text-xs font-medium text-mute">Descrição completa</p>
            <div class="mt-2 whitespace-pre-line text-sm leading-7 text-ink">{{ $product->description ?: 'Sem descrição registada.' }}</div>
        </div>
    </section>

    {{-- Ingredientes e alergénios --}}
    <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
        <h2 class="text-base font-bold text-ink">Ingredientes e segurança alimentar</h2>

        <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <p class="text-xs font-medium text-mute">Ingredientes</p>
                <div class="mt-2 whitespace-pre-line text-sm leading-6 text-ink">{{ $product->ingredients ?: 'Não especificados.' }}</div>
            </div>

            <div>
                <p class="text-xs font-medium text-mute">Alergénios</p>
                <div class="mt-2 whitespace-pre-line text-sm leading-6 text-ink">{{ $product->allergens ?: 'Não especificados.' }}</div>
            </div>

            <div>
                <p class="text-xs font-medium text-mute">Aviso de contaminação cruzada</p>
                <div class="mt-2 whitespace-pre-line text-sm leading-6 text-ink">{{ $product->cross_contamination_notice ?: 'Sem aviso registado.' }}</div>
            </div>

            <div>
                <p class="text-xs font-medium text-mute">Conservação</p>
                <div class="mt-2 whitespace-pre-line text-sm leading-6 text-ink">{{ $product->storage_info ?: 'Sem informação de conservação.' }}</div>
            </div>

            <div>
                <p class="text-xs font-medium text-mute">Preparação</p>
                <div class="mt-2 whitespace-pre-line text-sm leading-6 text-ink">{{ $product->preparation_info ?: 'Sem instruções de preparação.' }}</div>
            </div>
        </div>
    </section>

    {{-- Informação nutricional --}}
    <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
        <h2 class="text-base font-bold text-ink">Informação nutricional</h2>
        <p class="mt-1 text-sm text-mute">Valores registados para este produto.</p>

        @if(is_array($product->nutrition) && count($product->nutrition))
            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[320px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-mute">
                            <th class="px-3 py-3 font-semibold">Nutriente</th>
                            <th class="px-3 py-3 font-semibold">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->nutrition as $nutrient => $value)
                            <tr class="border-b border-line last:border-0">
                                <td class="px-3 py-3 font-medium text-ink">
                                    {{ is_string($nutrient) ? ucfirst(str_replace('_', ' ', $nutrient)) : 'Nutriente' }}
                                </td>
                                <td class="px-3 py-3 text-ink">
                                    {{ is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mt-4 rounded-xl border border-dashed border-line p-5 text-sm text-mute">
                Não existe informação nutricional registada.
            </p>
        @endif
    </section>

    {{-- Stock detalhado --}}
    <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
        <h2 class="text-base font-bold text-ink">Gestão de stock</h2>
        <p class="mt-1 text-sm text-mute">Resumo das quantidades registadas.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-line p-4">
                <p class="text-sm text-mute">Stock físico</p>
                <p class="mt-2 text-xl font-bold text-ink">
                    {{ number_format($product->quantity_on_hand) }}
                </p>
            </div>

            <div class="rounded-xl border border-line p-4">
                <p class="text-sm text-mute">Reservado</p>
                <p class="mt-2 text-xl font-bold text-ink">
                    {{ number_format($product->quantity_reserved) }}
                </p>
            </div>

            <div class="rounded-xl border border-line p-4">
                <p class="text-sm text-mute">Indisponível</p>
                <p class="mt-2 text-xl font-bold text-ink">
                    {{ number_format($product->quantity_unavailable) }}
                </p>
            </div>

            <div class="rounded-xl border border-line p-4">
                <p class="text-sm text-mute">Limite de stock baixo</p>
                <p class="mt-2 text-xl font-bold text-ink">
                    {{ number_format($product->low_stock_threshold) }}
                </p>
            </div>
        </div>

        @if(!$product->track_stock)
            <p class="mt-4 rounded-xl border border-line bg-surface p-4 text-sm text-mute">
                O controlo de stock está desativado para este produto.
            </p>
        @elseif($stockStatus === 'out' || $stockStatus === 'low')
            <p class="mt-4 rounded-xl border {{ $stockStatus === 'out' ? 'border-red-200 bg-red-50 text-red-700' : 'border-amber-200 bg-amber-50 text-amber-700' }} p-4 text-sm">
                {{ $stockStatus === 'out'
                    ? 'Atenção: não existe stock disponível para venda.'
                    : 'Atenção: o stock disponível atingiu o limite mínimo definido.' }}
            </p>
        @endif
    </section>

    {{-- Últimos movimentos --}}
    <section class="rounded-2xl border border-line bg-surface p-5 sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-bold text-ink">Movimentos de stock</h2>
                <p class="mt-1 text-sm text-mute">Últimos movimentos registados para este produto.</p>
            </div>
        </div>

        @if($product->stockMovements->isNotEmpty())
            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line text-mute">
                            <th class="px-3 py-3 font-semibold">Data</th>
                            <th class="px-3 py-3 font-semibold">Tipo</th>
                            <th class="px-3 py-3 text-right font-semibold">Variação física</th>
                            <th class="px-3 py-3 text-right font-semibold">Reservado</th>
                            <th class="px-3 py-3 text-right font-semibold">Indisponível</th>
                            <th class="px-3 py-3 font-semibold">Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->stockMovements as $movement)
                            @php
                                $movementType = $movement->type instanceof \BackedEnum
                                    ? $movement->type->value
                                    : $movement->type;
                            @endphp
                            <tr class="border-b border-line last:border-0">
                                <td class="whitespace-nowrap px-3 py-3 text-ink">
                                    {{ $movement->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-3 py-3 font-medium text-ink">
                                    {{ ucfirst(str_replace('_', ' ', $movementType ?? '—')) }}
                                </td>
                                <td class="px-3 py-3 text-right font-semibold {{ $movement->on_hand_delta > 0 ? 'text-emerald-600' : ($movement->on_hand_delta < 0 ? 'text-red-600' : 'text-ink') }}">
                                    {{ $movement->on_hand_delta > 0 ? '+' : '' }}{{ number_format($movement->on_hand_delta) }}
                                </td>
                                <td class="px-3 py-3 text-right text-ink">
                                    {{ $movement->reserved_delta > 0 ? '+' : '' }}{{ number_format($movement->reserved_delta) }}
                                </td>
                                <td class="px-3 py-3 text-right text-ink">
                                    {{ $movement->unavailable_delta > 0 ? '+' : '' }}{{ number_format($movement->unavailable_delta) }}
                                </td>
                                <td class="max-w-xs whitespace-normal px-3 py-3 text-mute">
                                    {{ $movement->reason ?: '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mt-5 rounded-xl border border-dashed border-line p-5 text-sm text-mute">
                Ainda não existem movimentos de stock para este produto.
            </p>
        @endif
    </section>

    {{-- Ações finais --}}
    <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-between">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-semibold text-ink transition hover:bg-surface">
            Voltar
        </a>

        <a href="#"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
            Editar produto
        </a>
    </div>

</div>
@endsection