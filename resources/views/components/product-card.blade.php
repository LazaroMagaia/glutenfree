@props(['product'])

<article class="reveal group rounded-[2rem] border border-line bg-surface p-3 transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-black/10">
    <x-photo :id="$product['img']" :emoji="$product['emoji']" :alt="$product['name']" class="ph h-52 rounded-3xl">
        @if (!empty($product['tag']))
            <span class="absolute left-3 top-3 rounded-full bg-accent px-3 py-1 text-xs font-semibold text-black">{{ $product['tag'] }}</span>
        @endif
    </x-photo>
    <div class="p-3">
        <h3 class="font-display text-lg font-extrabold">{{ $product['name'] }}</h3>
        <p class="text-sm text-mute">{{ $product['desc'] }}</p>
        <div class="mt-4 flex items-center justify-between">
            <span class="font-display text-xl font-extrabold text-brand">{{ number_format($product['price'], 0, ',', ' ') }} MZN</span>
            <button x-data @click="$store.cart.add({{ Js::from($product['name']) }})" aria-label="Adicionar {{ $product['name'] }}"
                    class="grid h-11 w-11 place-items-center rounded-full bg-brand text-xl text-brandink transition hover:scale-110">+</button>
        </div>
    </div>
</article>
