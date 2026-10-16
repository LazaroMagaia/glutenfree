@extends('layouts.backend')

@section('title', 'Gluten Free · Stock de ' . $product->name)

@php
    use App\Enums\StockMovementType;

    $routeParams = ['category' => $categorySlug, 'slug' => $product->slug];

    $statusBadge = match ($product->stock_status) {
        'ok'        => ['wrapper' => 'bg-green-500/10 text-green-600', 'dot' => 'bg-green-500', 'label' => 'Em stock'],
        'low'       => ['wrapper' => 'bg-amber-500/10 text-amber-600', 'dot' => 'bg-amber-500', 'label' => 'Stock baixo'],
        'out'       => ['wrapper' => 'bg-red-500/10 text-red-500', 'dot' => 'bg-red-500', 'label' => 'Esgotado'],
        default     => ['wrapper' => 'bg-soft text-mute', 'dot' => 'bg-gray-400', 'label' => 'Sem controlo'],
    };

    $fmt = fn (int $n) => ($n > 0 ? '+' : '') . $n;
@endphp

@section('content')
<section class="space-y-6">

    @include('components.flash')
    @include('Backend.Admin.Products.Stock.modal.store', ['routeParams' => $routeParams, 'manualTypes' => $manualTypes, 'idempotencyKey' => $idempotencyKey])
    {{-- Cabeçalho --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <a href="{{ route('admin.products.index', ['category' => $categorySlug]) }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-mute transition hover:text-ink">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                Voltar aos produtos
            </a>

            <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
                Gestão de stock
            </h1>

            <p class="mt-2 flex flex-wrap items-center gap-2 text-sm text-mute">
                <span class="font-semibold text-ink">{{ $product->name }}</span>
                <span>·</span>
                <span>{{ $product->sku }}</span>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $statusBadge['wrapper'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $statusBadge['dot'] }}"></span>
                    {{ $statusBadge['label'] }}
                </span>
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">

        @if($product->track_stock)
             <button
                type="button"
                data-modal-open="create-stock-movement-modal"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                Novo movimento
            </button>
        @endif
        </div>
    </div>

   

    {{-- Saldos --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-line bg-surface p-5">
            <span class="text-sm font-medium text-mute">Stock físico</span>
            <p class="mt-4 text-3xl font-extrabold text-ink">{{ $product->quantity_on_hand }}</p>
            <p class="mt-1 text-xs text-mute">Unidades em armazém</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <span class="text-sm font-medium text-mute">Reservado</span>
            <p class="mt-4 text-3xl font-extrabold text-blue-600">{{ $product->quantity_reserved }}</p>
            <p class="mt-1 text-xs text-mute">Comprometido com encomendas</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <span class="text-sm font-medium text-mute">Indisponível</span>
            <p class="mt-4 text-3xl font-extrabold text-amber-600">{{ $product->quantity_unavailable }}</p>
            <p class="mt-1 text-xs text-mute">Danificado ou em quarentena</p>
        </div>

        <div class="rounded-2xl border border-line bg-surface p-5">
            <span class="text-sm font-medium text-mute">Disponível para venda</span>
            <p class="mt-4 text-3xl font-extrabold text-green-600">{{ $product->available }}</p>
            <p class="mt-1 text-xs text-mute">
                @if($product->low_stock_threshold > 0)
                    Alerta abaixo de {{ $product->low_stock_threshold }}
                @else
                    Sem alerta de stock baixo
                @endif
            </p>
        </div>

    </div>

    {{-- Histórico --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-surface">

        <div class="border-b border-line p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-ink">Histórico de movimentos</h2>
                    <p class="mt-1 text-sm text-mute">Registo permanente: os movimentos não podem ser editados nem eliminados.</p>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <select name="type"
                            class="rounded-xl border-line bg-bg py-2.5 text-sm text-ink focus:border-brand focus:ring-brand">
                        <option value="">Todos os tipos</option>
                        @foreach($allTypes as $t)
                            <option value="{{ $t->value }}" @selected($filterType === $t)>{{ $t->label() }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="rounded-xl border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-soft">
                        Filtrar
                    </button>

                    @if(request()->filled('type'))
                        <a href="{{ url()->current() }}"
                           class="rounded-xl px-3 py-2.5 text-center text-sm font-semibold text-mute hover:bg-soft">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left text-sm">

                <thead class="bg-soft/60 text-xs uppercase tracking-wider text-mute">
                    <tr>
                        <th class="px-5 py-4 font-bold">Data</th>
                        <th class="px-5 py-4 font-bold">Tipo</th>
                        <th class="px-5 py-4 font-bold">Variação</th>
                        <th class="px-5 py-4 font-bold">Stock depois</th>
                        <th class="px-5 py-4 font-bold">Motivo</th>
                        <th class="px-5 py-4 font-bold">Utilizador</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-line">
                    @forelse($movements as $movement)
                        @php
                            $typeBadge = match ($movement->type) {
                                StockMovementType::Entry, StockMovementType::Sale => 'bg-green-500/10 text-green-600',
                                StockMovementType::Adjustment, StockMovementType::Reservation => 'bg-blue-500/10 text-blue-600',
                                StockMovementType::Damage => 'bg-amber-500/10 text-amber-600',
                                StockMovementType::Loss, StockMovementType::Discard => 'bg-red-500/10 text-red-500',
                                default => 'bg-soft text-mute',
                            };
                        @endphp

                        <tr class="transition hover:bg-soft/40">
                            <td class="whitespace-nowrap px-5 py-4 text-mute">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $typeBadge }}">
                                    {{ $movement->type->label() }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @if($movement->on_hand_delta !== 0)
                                        <span class="rounded-full bg-soft px-2.5 py-1 text-xs font-semibold text-ink">
                                            Físico {{ $fmt($movement->on_hand_delta) }}
                                        </span>
                                    @endif
                                    @if($movement->reserved_delta !== 0)
                                        <span class="rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                            Reservado {{ $fmt($movement->reserved_delta) }}
                                        </span>
                                    @endif
                                    @if($movement->unavailable_delta !== 0)
                                        <span class="rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-600">
                                            Indisponível {{ $fmt($movement->unavailable_delta) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-bold text-ink">{{ $movement->on_hand_after }}</p>
                                <p class="mt-0.5 text-xs text-mute">
                                    R: {{ $movement->reserved_after }} · I: {{ $movement->unavailable_after }}
                                </p>
                            </td>

                            <td class="max-w-xs px-5 py-4 text-mute">
                                {{ $movement->reason ?: '—' }}
                            </td>

                            <td class="px-5 py-4 text-mute">
                                {{ $movement->user?->name ?? 'Sistema' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-soft text-mute">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h12l3 6H3l3-6z"/><path d="M3 9v11h18V9"/>
                                    </svg>
                                </div>
                                <h3 class="mt-4 font-bold text-ink">Nenhum movimento encontrado</h3>
                                <p class="mt-1 text-sm text-mute">Os movimentos de stock deste produto vão aparecer aqui.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="border-t border-line px-5 py-4">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</section>
@endsection