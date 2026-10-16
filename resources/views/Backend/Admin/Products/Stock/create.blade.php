@extends('layouts.backend')

@section('title', 'Gluten Free · Novo movimento de stock')

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
            Novo movimento de stock
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

    <form action="{{ route('admin.products.stock.store', $routeParams) }}"
          method="POST"
          class="space-y-6"
          x-data="stockForm({
              type: @js(old('type', '')),
              quantity: @js(old('quantity', '')),
              onHand: {{ $product->quantity_on_hand }},
              unavailable: {{ $product->quantity_unavailable }},
          })">
        @csrf

        <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', $idempotencyKey) }}">

        {{-- Saldo atual --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="rounded-2xl border border-line bg-surface p-4">
                <p class="text-xs font-medium text-mute">Stock físico</p>
                <p class="mt-2 text-2xl font-extrabold text-ink">{{ $product->quantity_on_hand }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-4">
                <p class="text-xs font-medium text-mute">Reservado</p>
                <p class="mt-2 text-2xl font-extrabold text-blue-600">{{ $product->quantity_reserved }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-4">
                <p class="text-xs font-medium text-mute">Indisponível</p>
                <p class="mt-2 text-2xl font-extrabold text-amber-600">{{ $product->quantity_unavailable }}</p>
            </div>
        </div>

        {{-- Movimento --}}
        <div class="overflow-hidden rounded-2xl border border-line bg-surface">
            <div class="border-b border-line p-5 sm:p-6">
                <h2 class="font-bold text-ink">Dados do movimento</h2>
                <p class="mt-1 text-xs text-mute">Depois de registado, o movimento não pode ser alterado.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                <div class="sm:col-span-2">
                    <label for="type" class="mb-2 block text-sm font-semibold text-ink">
                        Tipo de movimento <span class="text-red-500">*</span>
                    </label>

                    <select id="type" name="type" x-model="type" required
                            class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink focus:border-brand focus:ring-brand
                            @error('type') border-red-500 @enderror">
                        <option value="">Seleciona o tipo</option>
                        @foreach($manualTypes as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>

                    @error('type')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-mute" x-show="hint" x-text="hint"></p>
                </div>

                <div>
                    <label for="quantity" class="mb-2 block text-sm font-semibold text-ink">
                        <span x-text="type === 'adjustment' ? 'Total contado' : 'Quantidade'">Quantidade</span>
                        <span class="text-red-500">*</span>
                    </label>

                    <input id="quantity" name="quantity" type="number" inputmode="numeric" min="0" step="1"
                           x-model="quantity" required placeholder="Ex.: 24"
                           class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                           focus:border-brand focus:ring-brand @error('quantity') border-red-500 @enderror">

                    @error('quantity')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pré-visualização --}}
                <div class="flex items-end">
                    <div class="w-full rounded-xl bg-soft px-4 py-3" x-show="preview" x-cloak>
                        <p class="text-xs font-medium text-mute" x-text="preview?.label"></p>
                        <p class="mt-1 text-xl font-extrabold"
                           :class="preview && preview.value < 0 ? 'text-red-500' : 'text-ink'"
                           x-text="preview?.value"></p>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-semibold text-ink">
                        Motivo
                        <span class="text-red-500" x-show="reasonRequired" x-cloak>*</span>
                    </label>

                    <textarea id="reason" name="reason" rows="3" maxlength="500"
                              :required="reasonRequired"
                              placeholder="Ex.: Contagem física de outubro, embalagem rasgada, fornecedor X..."
                              class="w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute
                              focus:border-brand focus:ring-brand @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>

                    @error('reason')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Ações --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.products.stock', $routeParams) }}"
               class="inline-flex items-center justify-center rounded-xl border border-line px-5 py-3 text-sm font-bold text-ink transition hover:bg-soft">
                Cancelar
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                Registar movimento
            </button>
        </div>

    </form>
</section>
@endsection

@push('scripts')
<script>
function stockForm(cfg) {
    const hints = {
        entry: 'Quantidade a adicionar ao stock (ex.: chegada de fornecedor).',
        adjustment: 'Indica o total contado fisicamente. Substitui o stock atual.',
        loss: 'Quantidade a retirar do stock (perda, roubo, validade).',
        damage: 'Quantidade que passa a indisponível. Continua fisicamente em armazém.',
        discard: 'Quantidade de produtos danificados a eliminar do stock.',
    };

    return {
        ...cfg,

        get q() { return parseInt(this.quantity, 10) || 0; },
        get hint() { return hints[this.type] ?? ''; },
        get reasonRequired() { return ['adjustment', 'loss', 'damage', 'discard'].includes(this.type); },

        get preview() {
            switch (this.type) {
                case 'entry':      return { label: 'Stock físico depois', value: this.onHand + this.q };
                case 'adjustment': return { label: 'Stock físico depois', value: this.q };
                case 'loss':
                case 'discard':    return { label: 'Stock físico depois', value: this.onHand - this.q };
                case 'damage':     return { label: 'Indisponível depois', value: this.unavailable + this.q };
                default:           return null;
            }
        },
    };
}
</script>
@endpush