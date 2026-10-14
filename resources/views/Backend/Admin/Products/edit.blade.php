@extends('layouts.backend')

@section('title', 'Gluten Free · Editar produto')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ url()->previous() }}"
               class="mb-2 inline-flex items-center gap-2 text-sm text-mute transition hover:text-ink">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Voltar
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-ink">Editar produto</h1>
            <p class="mt-1 text-sm text-mute">{{ $product->name }}</p>
        </div>
    </div>

    @include('components.flash')

    <form action="{{ route('admin.products.update', ['slug' => $categorySlug, 'id' => $product->id]) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        @include('Backend.Admin.Products._form', [
            'product' => $product,
            'category' => $category,
        ])

        <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-semibold text-ink transition hover:bg-surface">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                Guardar alterações
            </button>
        </div>
    </form>
</div>
@endsection