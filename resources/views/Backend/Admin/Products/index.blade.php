@extends('layouts.backend')

@section('title', 'Gluten Free · Produtos')

@php
    use App\Enums\ProductStatus;

    $total = $products->count();
    $published = $products->filter(fn ($p) => $p->status === ProductStatus::Published)->count();
    $featured = $products->where('is_featured', true)->count();
    $new = $products->where('is_new', true)->count();

    $createUrl = route('admin.products.create', ['category' => $categorySlug]);
@endphp

@section('content')

<div id="toast-container" class="fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-2"></div>

<section class="space-y-6">

    @include('components.flash')
    @include('Backend.Admin.Products.Modals.delete')

    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm font-medium text-mute">Administração / Produtos</p>

            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Gestão de produtos
            </h1>

            <p class="mt-2 text-sm text-mute">
                Gere os produtos, categorias, informações nutricionais e disponibilidade.
            </p>
        </div>

        <a href="{{ $createUrl }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>
            Novo produto
        </a>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Total de produtos</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m16.5 9.4-9-5.19"/>
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $total }}</p>
            <p class="mt-1 text-xs text-mute">Produtos nesta categoria</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Produtos publicados</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $published }}</p>
            <p class="mt-1 text-xs text-mute">Visíveis no catálogo</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Em destaque</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $featured }}</p>
            <p class="mt-1 text-xs text-mute">Produtos destacados</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-mute">Novos produtos</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"/>
                        <path d="M5 12h14"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $new }}</p>
            <p class="mt-1 text-xs text-mute">Marcados como novidades</p>
        </div>

    </div>

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">

        <div class="border-b border-line p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-lg font-bold text-ink">Lista de produtos</h2>
                    <p class="mt-1 text-sm text-mute">Consulta e gere os produtos do catálogo.</p>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3 sm:flex-row">

                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-mute">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.34-4.34"/>
                        </svg>

                        <input type="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Nome ou slug do produto..."
                               class="w-full rounded-xl border-line bg-bg py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-mute focus:border-brand focus:ring-brand sm:w-64">
                    </div>

                    <button type="submit"
                            class="rounded-xl border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-soft">
                        Pesquisar
                    </button>

                    @if(request()->filled('search'))
                        <a href="{{ url()->current() }}"
                           class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold text-mute hover:bg-soft">
                            Limpar
                        </a>
                    @endif

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">

                <thead class="bg-soft/60 text-xs uppercase tracking-wider text-mute">
                    <tr>
                        <th class="px-5 py-4 font-bold">Produto</th>
                        <th class="px-5 py-4 font-bold">Categoria</th>
                        <th class="px-5 py-4 font-bold">Estado</th>
                        <th class="px-5 py-4 font-bold">Características</th>
                        <th class="px-5 py-4 font-bold">Criado em</th>
                        <th class="px-5 py-4 text-right font-bold">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line">

                    @forelse($products as $product)

                        @php
                            $routeParams = [
                                'categorySlug' => $categorySlug,
                                'productId' => $product->id,
                            ];

                            $status = $product->status;

                            $badge = match ($status) {
                                ProductStatus::Published => ['wrapper' => 'bg-green-500/10 text-green-600', 'dot' => 'bg-green-500'],
                                ProductStatus::Draft => ['wrapper' => 'bg-amber-500/10 text-amber-600', 'dot' => 'bg-amber-500'],
                                default => ['wrapper' => 'bg-soft text-mute', 'dot' => 'bg-gray-400'],
                            };

                            $thumb = $product->primaryImage
                                ? Storage::disk('public')->url($product->primaryImage->path)
                                : null;
                        @endphp

                        <tr class="transition hover:bg-soft/40">

                            {{-- Produto --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-soft text-ink">
                                        @if($thumb)
                                            <img src="{{ $thumb }}" alt="{{ $product->name }}"
                                                 class="h-full w-full object-cover" loading="lazy">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M6 3h12l3 6H3l3-6z"/>
                                                <path d="M3 9v11h18V9"/>
                                                <path d="M9 13h6"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-bold text-ink">{{ $product->name }}</p>
                                        <p class="mt-0.5 text-xs text-mute">{{ $product->slug }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Categoria --}}
                            <td class="px-5 py-4">
                                @if($product->category)
                                    <p class="font-medium text-ink">{{ $product->category->name }}</p>
                                    <p class="mt-0.5 text-xs text-mute">
                                        {{ $product->category->parent?->name ?? 'Categoria principal' }}
                                    </p>
                                @else
                                    <span class="text-mute">Sem categoria</span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $badge['wrapper'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                    {{ $status?->label() ?? '—' }}
                                </span>
                            </td>

                            {{-- Características --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">

                                    @if($product->is_featured)
                                        <span class="rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-600">Destaque</span>
                                    @endif

                                    @if($product->is_new)
                                        <span class="rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-semibold text-blue-600">Novo</span>
                                    @endif

                                    @if($product->is_favorite)
                                        <span class="rounded-full bg-pink-500/10 px-2.5 py-1 text-xs font-semibold text-pink-600">Favorito</span>
                                    @endif

                                    @if($product->is_purchasable)
                                        <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-xs font-semibold text-green-600">Comprável</span>
                                    @endif

                                    @unless($product->is_featured || $product->is_new || $product->is_favorite || $product->is_purchasable)
                                        <span class="text-xs text-mute">—</span>
                                    @endunless

                                </div>
                            </td>

                            {{-- Data --}}
                            <td class="px-5 py-4 text-mute">
                                {{ $product->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            {{-- Ações --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.products.stock', ['category' => $categorySlug, 'slug' => $product->slug]) }}"
                                    title="Gestão de stock"
                                    aria-label="Gestão de stock de {{ $product->name }}"
                                    class="rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"/>
                                            <path d="m7 16.5-4.74-2.85"/>
                                            <path d="m7 16.5 5-3"/>
                                            <path d="M7 16.5v5.17"/>
                                            <path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"/>
                                            <path d="m17 16.5-5-3"/>
                                            <path d="m17 16.5 4.74-2.85"/>
                                            <path d="M17 16.5v5.17"/>
                                            <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"/>
                                            <path d="M12 8 7.26 5.15"/>
                                            <path d="m12 8 4.74-2.85"/>
                                            <path d="M12 13.5V8"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.products.show', ['category' => $categorySlug,'slug' => $product->slug,]) }}"
                                       title="Ver produto"
                                       aria-label="Ver produto {{ $product->name }}"
                                       class="rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.products.edit', ['id' => $product->id,'category' => $categorySlug,]) }}"
                                       title="Editar produto"
                                       aria-label="Editar produto {{ $product->name }}"
                                       class="rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="m18.375 2.625 3 3-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                                        </svg>
                                    </a>

                                    <button
                                        type="button"
                                        data-modal-open="delete-product-modal"
                                        data-delete-url="{{ route('admin.products.destroy', ['slug' => $categorySlug, 'id' => $product->id]) }}"
                                        data-delete-name="{{ $product->name }}"
                                        title="Eliminar produto"
                                        aria-label="Eliminar produto {{ $product->name }}"
                                        class="rounded-lg p-2 text-red-500 transition hover:bg-red-500/10"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4h8v2"/>
                                            <path d="m19 6-1 14H6L5 6"/>
                                            <path d="M10 11v5"/>
                                            <path d="M14 11v5"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-soft text-mute">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h12l3 6H3l3-6z"/>
                                        <path d="M3 9v11h18V9"/>
                                    </svg>
                                </div>

                                <h3 class="mt-4 font-bold text-ink">Nenhum produto encontrado</h3>

                                <p class="mt-1 text-sm text-mute">
                                    Tenta outra pesquisa ou adiciona um novo produto ao catálogo.
                                </p>

                                <a href="{{ $createUrl }}"
                                   class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-brandink transition hover:opacity-90">
                                    <span>+</span>
                                    Criar produto
                                </a>

                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>

</section>

@endsection