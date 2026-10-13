
@extends('layouts.backend')

@section('title', 'Gluten Free · Categorias de Produtos')

@section('content')

<div id="toast-container"
     class="fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-2">
</div>

<section class="space-y-6">

    @include('components.flash')
    @include('Backend.Admin.Products.Category.modal.category')
    @include('Backend.Admin.Products.Category.modal.edit-category')
    @include('Backend.Admin.Products.Category.modal.delete')
    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm font-medium text-mute">
                Administração / Categorias de produtos
            </p>

            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Gestão de categorias
            </h1>

            <p class="mt-2 text-sm text-mute">
                Organiza e gere as categorias dos teus produtos.
            </p>
        </div>

        <button
            type="button"
            data-modal-open="create-category-modal"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink
            shadow-sm transition hover:opacity-90"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>

            Nova categoria
        </button>
    </div>

    {{-- Estatísticas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-line bg-bg p-5">
            <p class="text-sm font-medium text-mute">
                Total de categorias
            </p>

            <h2 class="mt-2 text-3xl font-extrabold text-ink">
                {{ $categories->count() }}
            </h2>
        </div>

        <div class="rounded-2xl border border-line bg-bg p-5">
            <p class="text-sm font-medium text-mute">
                Categorias ativas
            </p>

            <h2 class="mt-2 text-3xl font-extrabold text-ink">
                {{ $categories->where('is_active', true)->count() }}
            </h2>
        </div>

        <div class="rounded-2xl border border-line bg-bg p-5">
            <p class="text-sm font-medium text-mute">
                Categorias inativas
            </p>

            <h2 class="mt-2 text-3xl font-extrabold text-ink">
                {{ $categories->where('is_active', false)->count() }}
            </h2>
        </div>

    </div>

    {{-- Lista de categorias --}}
    <div>
        <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-ink">
                Todas as categorias
            </h2>

            <span class="text-sm text-mute">
                {{ $categories->count() }}
                {{ $categories->count() === 1 ? 'categoria' : 'categorias' }}
            </span>
        </div>

        @if($categories->isNotEmpty())

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                @foreach($categories as $category)
                    <a href="{{ route('admin.products.index', $category->slug) }}"
                    >
                    <article
                        class="group overflow-hidden rounded-2xl border border-line bg-bg transition duration-200 hover:-translate-y-1 hover:shadow-lg"
                    >

                        {{-- Imagem --}}
                        <div class="relative h-48 overflow-hidden bg-soft">

                            @if($category->image)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) }}"
                                    alt="{{ $category->name }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-full w-full items-center justify-center text-mute">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="48" height="48"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor"
                                         stroke-width="1.5"
                                         stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <path d="m21 15-5-5L5 21"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Estado --}}
                            <div class="absolute right-3 top-3">
                                @if($category->is_active)
                                    <span class="rounded-full bg-green-500/10 px-3 py-1.5 text-xs font-bold text-green-600">
                                        Ativa
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-500/10 px-3 py-1.5 text-xs font-bold text-red-500">
                                        Inativa
                                    </span>
                                @endif
                            </div>

                        </div>

                        {{-- Conteúdo --}}
                        <div class="space-y-4 p-5">

                            <div>
                                <h3 class="truncate text-lg font-bold text-ink">
                                    {{ $category->name }}
                                </h3>

                                <p class="mt-1 text-xs text-mute">
                                    Slug: {{ $category->slug }}
                                </p>
                            </div>

                            <p class="min-h-10 text-sm leading-6 text-mute">
                                {{ $category->description ?: 'Sem descrição disponível.' }}
                            </p>

                            {{-- Informações adicionais --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs text-mute">

                                <span class="rounded-lg bg-soft px-3 py-2">
                                    Ordem: {{ $category->sort_order }}
                                </span>

                                @if($category->parent_id)
                                    <span class="rounded-lg bg-soft px-3 py-2">
                                        Subcategoria
                                    </span>
                                @else
                                    <span class="rounded-lg bg-soft px-3 py-2">
                                        Principal
                                    </span>
                                @endif

                            </div>

                            {{-- Ações --}}
                            <div class="flex items-center justify-between border-t border-line pt-4">

                            <button
                                type="button"
                                data-modal-open="edit-category-modal"
                                data-edit-url="{{ route('admin.products.categories.update', $category->slug) }}"
                                data-edit-name="{{ $category->name }}"
                                data-edit-parent="{{ $category->parent_id }}"
                                data-edit-description="{{ $category->description }}"
                                data-edit-image="{{ $category->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($category->image) : '' }}"
                                data-edit-sort-order="{{ $category->sort_order }}"
                                data-edit-active="{{ $category->is_active ? '1' : '0' }}"
                                data-edit-id="{{ $category->id }}"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-ink transition hover:bg-soft"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16"
                                    viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                </svg>

                                Editar
                            </button>

                            <button
                                type="button" data-modal-open="delete-category-modal"
                                    data-delete-url="{{ route('admin.products.categories.destroy', $category->slug) }}"
                                    data-delete-name="{{ $category->name }}"
                                    aria-label="Eliminar categoria {{ $category->name }}"
                                    title="Eliminar categoria"
                                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-red-500 transition hover:bg-red-500/10"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16"
                                        viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M8 6V4h8v2"/>
                                    <path d="m19 6-1 14H6L5 6"/>
                                    <path d="M10 11v5"/>
                                    <path d="M14 11v5"/>
                                </svg>

                                Eliminar
                            </button>

                            </div>

                        </div>

                    </article>
                    </a>

                @endforeach

            </div>

        @else

            {{-- Estado vazio --}}
            <div class="rounded-2xl border border-dashed border-line bg-bg px-6 py-16 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-soft text-mute">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="32" height="32"
                         viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="1.5"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <path d="M3 9h18"/>
                        <path d="M9 21V9"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-lg font-bold text-ink">
                    Ainda não existem categorias
                </h3>

                <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-mute">
                    Cria a tua primeira categoria para começares a organizar os produtos.
                </p>

                <button
                    type="button"
                    data-modal-open="create-category-modal"
                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-brand px-5 py-3 text-sm font-bold 
                    text-brandink transition hover:opacity-90"
                >
                    Criar primeira categoria
                </button>

            </div>

        @endif

    </div>

</section>

@endsection