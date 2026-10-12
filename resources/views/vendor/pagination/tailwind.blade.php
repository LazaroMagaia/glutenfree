
@if ($paginator->hasPages())
    <nav
        role="navigation"
        aria-label="{{ __('Pagination Navigation') }}"
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
    >
        {{-- Informação dos resultados --}}
        <p class="text-sm text-mute">
            A mostrar

            @if ($paginator->firstItem())
                <span class="font-semibold text-ink">
                    {{ $paginator->firstItem() }}
                </span>

                até

                <span class="font-semibold text-ink">
                    {{ $paginator->lastItem() }}
                </span>
            @else
                <span class="font-semibold text-ink">0</span>
            @endif

            de

            <span class="font-semibold text-ink">
                {{ $paginator->total() }}
            </span>

            resultados
        </p>

        {{-- Navegação --}}
        <div class="flex items-center justify-between gap-2 sm:justify-end">

            {{-- Página anterior --}}
            @if ($paginator->onFirstPage())
                <span
                    aria-disabled="true"
                    aria-label="{{ __('pagination.previous') }}"
                    class="inline-flex h-9 items-center gap-2 rounded-xl border border-line bg-surface px-3 text-sm font-medium text-mute opacity-50 cursor-not-allowed"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left preview-icon"><path d="m15 18-6-6 6-6"/></svg>
                    <span class="hidden sm:inline">Anterior</span>
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
                    aria-label="{{ __('pagination.previous') }}"
                    class="inline-flex h-9 items-center gap-2 rounded-xl border border-line bg-surface px-3 text-sm font-medium text-ink transition hover:bg-soft focus:outline-none focus:ring-2 focus:ring-brand/30"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left preview-icon"><path d="m15 18-6-6 6-6"/></svg>
                    <span class="hidden sm:inline">Anterior</span>
                </a>
            @endif

            {{-- Números das páginas --}}
            <div class="flex items-center gap-1">
                @foreach ($elements as $element)

                    {{-- Reticências --}}
                    @if (is_string($element))
                        <span
                            aria-disabled="true"
                            class="flex h-9 min-w-9 items-center justify-center px-2 text-sm text-mute"
                        >
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Links das páginas --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)

                            @if ($page == $paginator->currentPage())
                                <span
                                    aria-current="page"
                                    class="flex h-9 min-w-9 items-center justify-center rounded-xl border border-brand bg-brand px-3 text-sm font-bold text-brandink shadow-sm"
                                >
                                    {{ $page }}
                                </span>
                            @else
                                <a
                                    href="{{ $url }}"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                    class="flex h-9 min-w-9 items-center justify-center rounded-xl border border-transparent px-3 text-sm font-medium text-mute transition hover:border-line hover:bg-soft hover:text-ink focus:outline-none focus:ring-2 focus:ring-brand/30"
                                >
                                    {{ $page }}
                                </a>
                            @endif

                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Página seguinte --}}
            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    rel="next"
                    aria-label="{{ __('pagination.next') }}"
                    class="inline-flex h-9 items-center gap-2 rounded-xl border border-line bg-surface px-3 text-sm font-medium text-ink transition hover:bg-soft focus:outline-none focus:ring-2 focus:ring-brand/30"
                >
                    <span class="hidden sm:inline">Seguinte</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @else
                <span
                    aria-disabled="true"
                    aria-label="{{ __('pagination.next') }}"
                    class="inline-flex h-9 items-center gap-2 rounded-xl border border-line bg-surface px-3 text-sm font-medium text-mute opacity-50 cursor-not-allowed"
                >
                    <span class="hidden sm:inline">Seguinte</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon"><path d="m9 18 6-6-6-6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif