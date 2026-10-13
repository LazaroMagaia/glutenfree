
@props([
    'id',
    'title' => '',
    'size' => 'md',
    'closeable' => true,
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-6xl',
    ];
@endphp

<div
    id="{{ $id }}"
    data-modal
    class="fixed inset-0 z-[100] hidden items-center justify-center overflow-y-auto p-4"
    aria-hidden="true"
>
    {{-- Fundo --}}
    <div
        data-modal-backdrop
        class="absolute inset-0 bg-black/60 opacity-0 transition-opacity duration-200"
    ></div>

    {{-- Painel --}}
    <div
        data-modal-panel
        role="dialog"
        aria-modal="true"
        @if($title)
            aria-labelledby="{{ $id }}-title"
        @endif
        class="relative z-10 my-auto flex max-h-[90vh] w-full
        {{ $sizes[$size] ?? $sizes['md'] }}
        scale-95 flex-col overflow-hidden rounded-2xl border border-line
        bg-surface opacity-0 shadow-2xl transition-all duration-200"
    >
        {{-- Cabeçalho fixo --}}
        @if($title || $closeable)
            <div class="flex shrink-0 items-center justify-between gap-4 border-b border-line px-5 py-4">
                @if($title)
                    <h2
                        id="{{ $id }}-title"
                        class="text-lg font-bold text-ink"
                    >
                        {{ $title }}
                    </h2>
                @endif

                @if($closeable)
                    <button
                        type="button"
                        data-modal-close
                        aria-label="Fechar"
                        class="ml-auto rounded-lg p-2 text-mute transition hover:bg-soft hover:text-ink"
                    >
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                @endif
            </div>
        @endif

        {{-- Conteúdo com scroll interno --}}
        <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-5">
            {{ $slot }}
        </div>

        {{-- Rodapé fixo --}}
        @isset($footer)
            <div class="flex shrink-0 flex-col-reverse gap-3 border-t border-line px-5 py-4 sm:flex-row sm:justify-end">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
