@if ($paginator->hasPages())
    @php
        $btn = 'inline-flex h-10 items-center justify-center gap-1.5 rounded-xl border border-line bg-surface px-3.5 text-sm font-medium transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand/40';
        $btnOn = $btn . ' text-ink hover:bg-soft active:scale-[0.98]';
        $btnOff = $btn . ' cursor-not-allowed text-mute opacity-50';
        $num = 'flex h-9 min-w-9 items-center justify-center rounded-lg px-2.5 text-sm tabular-nums transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand/40';
    @endphp

    <nav
        role="navigation"
        aria-label="{{ __('Pagination Navigation') }}"
        class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between"
    >
        {{-- Informação dos resultados --}}
        <p class="text-sm text-mute">
            A mostrar
            <span class="font-semibold tabular-nums text-ink">{{ $paginator->firstItem() ?? 0 }}</span>
            @if ($paginator->firstItem())
                a <span class="font-semibold tabular-nums text-ink">{{ $paginator->lastItem() }}</span>
            @endif
            de
            <span class="font-semibold tabular-nums text-ink">{{ $paginator->total() }}</span>
            resultados
        </p>

        <div class="flex w-full items-center justify-between gap-2 sm:w-auto sm:justify-end">

            {{-- Página anterior --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="{{ $btnOff }}">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    <span>Anterior</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="{{ $btnOn }}">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    <span>Anterior</span>
                </a>
            @endif

            {{-- Mobile: indicador compacto --}}
            <span class="text-sm tabular-nums text-mute sm:hidden">
                <span class="font-semibold text-ink">{{ $paginator->currentPage() }}</span>
                /
                {{ $paginator->lastPage() }}
            </span>

            {{-- Desktop: números das páginas --}}
            <div class="hidden items-center gap-0.5 rounded-xl border border-line bg-soft p-1 sm:flex">
                @foreach ($elements as $element)

                    {{-- Reticências --}}
                    @if (is_string($element))
                        <span aria-hidden="true" class="flex h-9 min-w-6 items-center justify-center text-sm text-mute">…</span>
                    @endif

                    {{-- Links das páginas --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="{{ $num }} bg-brand font-bold text-brandink shadow-sm"
                                >{{ $page }}</span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    class="{{ $num }} font-medium text-mute hover:bg-surface hover:text-ink"
                                >{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Página seguinte --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="{{ $btnOn }}">
                    <span>Seguinte</span>
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="{{ $btnOff }}">
                    <span>Seguinte</span>
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif