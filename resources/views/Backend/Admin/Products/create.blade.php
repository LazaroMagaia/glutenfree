@extends('layouts.backend')

@section('title', 'Gluten Free · Novo produto')

@section('content')
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

            <h1 class="text-2xl font-bold tracking-tight text-ink">Novo produto</h1>
            <p class="mt-1 text-sm text-mute">
                Adiciona um produto ao catálogo Gluten Free.
            </p>
        </div>

        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-line px-3 py-1.5 text-xs font-medium text-mute">
            <span class="h-2 w-2 rounded-full bg-primary"></span>
            Novo registo
        </span>
    </div>

    @include('components.flash')

    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf

        @include('Backend.Admin.Products._form', ['product' => null])

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 border-t border-line pt-5 sm:flex-row sm:justify-end">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-semibold text-ink transition hover:bg-surface">
                Cancelar
            </a>

            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink
                    shadow-sm transition hover:opacity-90">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Criar produto
            </button>
        </div>
    </form>
</div>
@endsection