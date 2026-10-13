
@extends('layouts.backend')

@section('title', 'Gluten Free · Produtos')

@section('content')

<div id="toast-container"
     class="fixed right-4 top-4 z-50 flex w-full max-w-sm flex-col gap-2">
</div>

<section class="space-y-6">

    @include('components.flash')
    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <p class="text-sm font-medium text-mute">
                Administração /  Produtos
            </p>

            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Gestão de Produtos
            </h1>

            <p class="mt-2 text-sm text-mute">
                Organiza e gere as produtos.
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

            Nova produto
        </button>
    </div>

  

</section>

@endsection