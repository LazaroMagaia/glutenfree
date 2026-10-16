@extends('layouts.backend')

@section('title', 'Gluten Free · Definições de stock')

@php
    $routeParams = ['category' => $categorySlug, 'slug' => $product->slug];
@endphp

@section('content')
<section class="mx-auto max-w-3xl space-y-6">

    {{-- Cabeçalho --}}
    <div>
        <a href="{{ route('admin.products.stock', $routeParams) }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-mute transition hover:text-ink">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Voltar ao stock
        </a>

        <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Definições de stock
        </h1>

        <p class="mt-2 text-sm text-mute">
            <span class="font-semibold text-ink">{{ $product->name }}</span> · {{ $product->sku }}
        </p>
    </div>

    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/5 p-4 text-sm text-red-600">
            <div class="font-bold">Verifica os campos do formulário.</div>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.stock.update', $routeParams) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <h2 class="font-bold text-ink">Controlo de stock</h2>
                <p class="mt-1 text-xs text-mute">Define como o stock deste produto é acompanhado.</p>
            </div>

            <div class="space-y-6 p-5 sm:p-6">

                {{-- Controlar stock --}}
                <div>
                    <input type="hidden" name="track_stock" value="0">

                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="checkbox" name="track_stock" value="1"
                               @checked(old('track_stock', $product->track_stock))
                               class="mt-0.5 h-5 w-5 rounded border-line text-brand focus:ring-brand">
                        <span>
                            <span class="block text-sm font-semibold text-ink">Controlar stock deste produto</span>
                            <span class="mt-1 block text-xs text-mute">
                                Quando desativado, não é possível registar movimentos e o produto não tem alertas de stock.
                            </span>
                        </span>
                    </label>
                </div>

                {{-- Limite de stock baixo --}}
                <div>
                    <label for="low_stock_threshold" class="mb-2 block text-sm font-semibold text-ink">
                        Alerta de stock baixo <span class="text-red-500">*</span>
                    </label>

                    <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0" step="1"
                           inputmode="numeric" required
                           value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                           focus:border-brand focus:ring-brand sm:w-64 @error('low_stock_threshold') border-red-500 @enderror">

                    @error('low_stock_threshold')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-mute">
                        O produto é marcado como "Stock baixo" quando o disponível for igual ou inferior a este valor.
                        Usa 0 para desativar o alerta.
                    </p>
                </div>

            </div>
        </div>

        {{-- Aviso: quantidades --}}
        <div class="rounded-xl border border-line bg-soft/60 p-4 text-sm text-mute">
            As quantidades (físico, reservado e indisponível) não se alteram aqui.
            Para as corrigir, regista um
            <a href="{{ route('admin.products.stock.create', $routeParams) }}" class="font-semibold text-ink underline">novo movimento</a>,
            como um ajuste de contagem.
        </div>

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.products.stock', $routeParams) }}"
               class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-bold text-ink transition hover:bg-soft">
                Cancelar
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                Guardar definições
            </button>
        </div>

    </form>
</section>
@endsection