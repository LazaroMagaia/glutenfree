@extends('layouts.app')

@section('title', 'Gluten Free · Sabor sem glúten em Moçambique')

@php
    // Helper para imagens (placeholders Unsplash — trocar depois por imagens reais / BD)
    $img = fn ($id, $w = 900) => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w={$w}&q=70";

    $marquee = ['Sem glúten', 'Sem contaminação cruzada', 'Feito em Moçambique', 'Entrega em todo o país', 'Ingredientes naturais'];

    $categories = [
        ['Farinhas',        'Arroz, mandioca, milho e mais', '🌾', '1586201375761-83865001e31c', 'md:col-span-2 md:row-span-2'],
        ['Pães',            'Frescos e congelados',          '🍞', '1509440159596-0249088772ff', ''],
        ['Massas',          'Penne, espargete, lasanha',     '🍝', '1473093295043-cdd812d0e601', ''],
        ['Snacks e doces',  'Biscoitos, bolos e barras',     '🍪', '1499636136210-6f4ee915583e', 'md:col-span-2'],
    ];

    $products = [
        ['Farinha de arroz',   '1 kg · fina',          180, '🌾', '1586201375761-83865001e31c', 'Novo'],
        ['Pão de forma',       '400 g · fatiado',      320, '🍞', '1509440159596-0249088772ff', 'Fresco'],
        ['Massa penne',        '500 g · milho e arroz', 260, '🍝', '1473093295043-cdd812d0e601', ''],
        ['Biscoitos de coco',  '200 g · crocantes',    150, '🍪', '1499636136210-6f4ee915583e', 'Favorito'],
    ];

    $features = [
        ['🌿', '100% Natural',    'Sem conservantes artificiais ou aditivos químicos'],
        ['🏅', 'Certificado',     'Produto certificado e com garantia de qualidade'],
        ['🚚', 'Entrega rápida',  '24–48h em Maputo, envio nacional disponível'],
        ['♻️', 'Eco-friendly',    'Embalagens recicláveis e produção sustentável'],
    ];

    $steps = [
        ['01', 'Escolhe o produto',      'Explora as nossas farinhas naturais e seleciona o que precisas.'],
        ['02', 'Adiciona ao carrinho',   'Um clique e o produto é guardado no teu carrinho de compra.'],
        ['03', 'Recebe em casa',         'Entregamos em Maputo em 24–48h e para todo o país.'],
    ];

    $stats = [['40+', 'Produtos'], ['100%', 'Sem glúten'], ['24h', 'Entrega Maputo']];

    $recipes = [
        ['🥥', '45 min · Fácil', 'Bolo de mandioca e coco', 'Húmido, doce e perfeito para o chá da tarde.',        '1578985545062-69928b1d9587'],
        ['🍤', '30 min · Fácil', 'Matapa com camarão',      'Um clássico moçambicano, naturalmente sem glúten.',   '1565557623262-b51c2513a641'],
        ['🥞', '20 min · Fácil', 'Panquecas de arroz',      'Leves e fofas, ideais para o pequeno-almoço.',        '1567620905732-2d1ec7ab7445'],
        ['🍝', '25 min · Fácil', 'Massa com legumes',       'Rápida e cheia de cor para o dia a dia.',             '1473093295043-cdd812d0e601'],
    ];

    $community = [
        ['🥗', '1490645935967-10de6ba17061'], ['🍲', '1504674900247-0877df9cc836'], ['🍕', '1565299624946-b28f40a0ae38'],
        ['🎂', '1578985545062-69928b1d9587'], ['🥞', '1567620905732-2d1ec7ab7445'], ['🍪', '1499636136210-6f4ee915583e'],
    ];

    $reviews = [
        ['Ana Machava',   'Maputo', 'Finalmente encontrei pão sem glúten com sabor de verdade. A minha filha adora.'],
        ['Carlos Sitoe',  'Matola', 'A farinha de arroz é fina e rende muito. A entrega chegou no dia seguinte.'],
        ['Marta Jossias', 'Beira',  'Como celíaca, a confiança é tudo. Os rótulos são claros e o apoio responde rápido.'],
    ];

    $faqs = [
        ['Os produtos são seguros para celíacos?',      'Sim. Trabalhamos com ingredientes sem glúten e evitamos a contaminação cruzada na produção. Consulte sempre o rótulo de cada produto.'],
        ['Quanto tempo demora a entrega?',              'Em Maputo entregamos em 24–48h. Para as outras províncias o envio nacional depende da localidade.'],
        ['Quais as formas de pagamento?',               'Aceitamos M-Pesa, e-Mola e transferência bancária. Confirmamos o pedido por WhatsApp.'],
        ['Posso fazer encomendas grandes ou revender?', 'Sim. Fale connosco por WhatsApp para preços de revenda e encomendas em quantidade.'],
        ['Como devo guardar as farinhas?',              'Num local fresco e seco, em recipiente bem fechado, para manter a qualidade por mais tempo.'],
    ];
@endphp

@section('content')

{{-- HERO --}}
<section class="relative mx-auto max-w-6xl px-5 pb-16 pt-32 md:pt-40">
    <div class="glow pointer-events-none absolute -left-20 top-20 h-80 w-80 rounded-full"></div>
    <div class="relative grid items-center gap-12 lg:grid-cols-[1.1fr_.9fr]">
        <div class="reveal">
            <span class="glass inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold"><span class="h-2 w-2 animate-pulse rounded-full bg-accent"></span>Novo · Entrega em todo o país</span>
            <h1 class="mt-6 font-display text-5xl font-extrabold leading-[.95] sm:text-6xl lg:text-7xl">Sabor de verdade.<br><span class="text-brand">Zero glúten.</span></h1>
            <p class="mt-6 max-w-lg text-lg text-mute">Farinhas, pães, massas e doces seguros para celíacos, feitos com ingredientes naturais e muito cuidado.</p>
            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="#produtos" class="group inline-flex items-center gap-3 rounded-full bg-brand py-3 pl-7 pr-3 font-semibold text-brandink shadow-xl shadow-black/10 transition hover:-translate-y-0.5">Comprar agora <span class="grid h-9 w-9 place-items-center rounded-full bg-accent text-black transition group-hover:rotate-45">↗</span></a>
                <a href="#receitas" class="rounded-full border border-line px-7 py-3.5 font-semibold hover:bg-soft">Ver receitas</a>
            </div>
            <div class="mt-10 flex items-center gap-4">
                <div class="flex -space-x-3">
                    <span class="grid h-10 w-10 place-items-center rounded-full border-2 border-bg bg-accent">🌾</span>
                    <span class="grid h-10 w-10 place-items-center rounded-full border-2 border-bg bg-soft">🍞</span>
                    <span class="grid h-10 w-10 place-items-center rounded-full border-2 border-bg bg-brand text-brandink">🍪</span>
                </div>
                <p class="text-sm text-mute"><b class="text-ink">+500 famílias</b> já comem sem glúten connosco</p>
            </div>
        </div>

        <div class="reveal relative mx-auto w-full max-w-md lg:max-w-none">
            <div class="ph aspect-[4/5] rounded-[3rem] shadow-2xl shadow-black/20"><span>🍞</span><img src="{{ $img('1509440159596-0249088772ff') }}" alt="Pão sem glúten" loading="lazy" onerror="this.remove()"></div>
            <div class="glass fl absolute -left-4 top-10 flex items-center gap-3 rounded-2xl p-3 pr-5 shadow-xl sm:-left-10">
                <img src="{{ asset('images/logo.png') }}" alt="" class="h-11 w-11 rounded-full">
                <div><p class="text-sm font-bold leading-tight">Seguro para celíacos</p><p class="text-xs text-mute">Sem contaminação cruzada</p></div>
            </div>
            <div class="glass fl absolute -right-3 bottom-16 rounded-2xl p-4 shadow-xl sm:-right-8" style="animation-delay:-2s">
                <p class="font-display text-3xl font-extrabold text-brand">4.9★</p><p class="text-xs text-mute">Avaliação média</p>
            </div>
        </div>
    </div>
</section>

{{-- MARQUEE --}}
<div class="overflow-hidden border-y border-line bg-brand py-4 text-brandink">
    <div class="marq font-display text-xl font-extrabold">
        @for ($i = 0; $i < 2; $i++)
            @foreach ($marquee as $text)
                <span class="mx-6 flex items-center gap-6">{{ $text }}<i class="h-2 w-2 rounded-full bg-accent"></i></span>
            @endforeach
        @endfor
    </div>
</div>

{{-- CATEGORIAS --}}
<section id="categorias" class="mx-auto max-w-6xl px-5 py-24">
    <div class="reveal mb-10 max-w-xl"><p class="text-sm font-semibold uppercase tracking-widest text-accent">Categorias</p><h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">O que procura hoje?</h2></div>
    <div class="reveal grid auto-rows-[200px] gap-4 md:auto-rows-[260px] md:grid-cols-4">
        @foreach ($categories as [$name, $desc, $emoji, $photo, $span])
            <a href="#produtos" class="ph group rounded-[2rem] {{ $span }}">
                <span>{{ $emoji }}</span>
                <img class="transition duration-700 group-hover:scale-110" src="{{ $img($photo) }}" alt="{{ $name }}" loading="lazy" onerror="this.remove()">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-6 text-left text-white"><h3 class="font-display text-2xl font-extrabold">{{ $name }}</h3><p class="text-sm opacity-80">{{ $desc }}</p></div>
                <span class="absolute right-4 top-4 grid h-10 w-10 place-items-center rounded-full bg-white/90 text-base text-black transition group-hover:rotate-45">↗</span>
            </a>
        @endforeach
    </div>
</section>

{{-- PRODUTOS --}}
<section id="produtos" class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-sm font-semibold uppercase tracking-widest text-accent">Em destaque</p><h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Os favoritos</h2></div>
        <a href="#contacto" class="rounded-full border border-line px-5 py-2.5 text-sm font-semibold hover:bg-soft">Ver tudo →</a>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($products as [$name, $desc, $price, $emoji, $photo, $tag])
            <article class="reveal group rounded-[2rem] border border-line bg-surface p-3 transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-black/10">
                <div class="ph h-52 rounded-3xl">
                    <span>{{ $emoji }}</span>
                    <img class="transition duration-700 group-hover:scale-110" src="{{ $img($photo, 700) }}" alt="{{ $name }}" loading="lazy" onerror="this.remove()">
                    @if ($tag)
                        <span class="absolute left-3 top-3 rounded-full bg-accent px-3 py-1 text-xs font-semibold text-black">{{ $tag }}</span>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-display text-lg font-extrabold">{{ $name }}</h3>
                    <p class="text-sm text-mute">{{ $desc }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="font-display text-xl font-extrabold text-brand">{{ number_format($price, 0, ',', ' ') }} MZN</span>
                        <button type="button" data-add="{{ $name }}" aria-label="Adicionar {{ $name }}" class="grid h-11 w-11 place-items-center rounded-full bg-brand text-xl text-brandink transition hover:scale-110">+</button>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- QUALIDADE --}}
<section class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-accent">Qualidade garantida</p>
        <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Porque escolher os nossos produtos?</h2>
        <p class="mt-3 text-mute">Produção artesanal com padrões internacionais de qualidade e sustentabilidade.</p>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($features as [$emoji, $title, $text])
            <div class="reveal rounded-[2rem] border border-line bg-surface p-7 transition hover:-translate-y-1 hover:border-accent">
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-soft text-3xl">{{ $emoji }}</div>
                <h3 class="mt-5 font-display text-xl font-extrabold">{{ $title }}</h3>
                <p class="mt-2 text-sm text-mute">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- COMO FUNCIONA --}}
<section class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-accent">Processo simples</p>
        <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Como funciona</h2>
        <p class="mt-3 text-mute">Compra em 3 passos rápidos e seguros</p>
    </div>
    <div class="grid gap-5 md:grid-cols-3">
        @foreach ($steps as [$num, $title, $text])
            <div class="reveal relative overflow-hidden rounded-[2rem] bg-soft p-8">
                <span class="font-display text-7xl font-extrabold text-accent/60">{{ $num }}</span>
                <h3 class="mt-4 font-display text-2xl font-extrabold">{{ $title }}</h3>
                <p class="mt-2 text-mute">{{ $text }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- NÚMEROS --}}
<section class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal relative overflow-hidden rounded-[2.5rem] bg-brand p-8 text-brandink md:p-14">
        <div class="glow absolute -right-10 -top-10 h-72 w-72 rounded-full"></div>
        <div class="relative grid gap-10 md:grid-cols-2">
            <div>
                <h2 class="font-display text-4xl font-extrabold leading-tight sm:text-5xl">Cuidado em cada passo, da farinha à sua mesa.</h2>
                <p class="mt-4 max-w-md opacity-80">Fornecedores selecionados, rótulos claros e produção separada para evitar qualquer contacto com glúten.</p>
            </div>
            <div class="grid grid-cols-3 gap-3 self-end text-center">
                @foreach ($stats as [$value, $label])
                    <div class="rounded-3xl bg-black/15 p-4"><p class="font-display text-3xl font-extrabold sm:text-4xl">{{ $value }}</p><p class="text-xs opacity-80">{{ $label }}</p></div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- RECEITAS --}}
<section id="receitas" class="mx-auto max-w-6xl py-4 pb-24">
    <div class="reveal mb-10 px-5"><p class="text-sm font-semibold uppercase tracking-widest text-accent">Receitas</p><h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Inspire-se na cozinha</h2></div>
    <div class="noscroll flex snap-x gap-5 overflow-x-auto px-5 pb-2">
        @foreach ($recipes as [$emoji, $meta, $title, $text, $photo])
            <article class="reveal w-72 shrink-0 snap-start overflow-hidden rounded-[2rem] border border-line bg-surface sm:w-80">
                <div class="ph h-48"><span>{{ $emoji }}</span><img src="{{ $img($photo) }}" alt="" loading="lazy" onerror="this.remove()"></div>
                <div class="p-6">
                    <span class="rounded-full bg-soft px-3 py-1 text-xs font-semibold text-brand">{{ $meta }}</span>
                    <h3 class="mt-3 font-display text-xl font-extrabold">{{ $title }}</h3>
                    <p class="mt-1 text-sm text-mute">{{ $text }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- COMUNIDADE --}}
<section class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-accent">Comunidade</p>
        <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Instagram &amp; TikTok</h2>
        <p class="mt-3 text-mute">Receitas, bastidores e novidades. Siga-nos @@glutenfree.mz</p>
    </div>
    <div class="reveal grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach ($community as [$emoji, $photo])
            <div class="ph group aspect-square rounded-3xl"><span>{{ $emoji }}</span><img class="transition duration-700 group-hover:scale-110" src="{{ $img($photo, 600) }}" alt="" loading="lazy" onerror="this.remove()"></div>
        @endforeach
    </div>
    <div class="mt-8 flex justify-center gap-3">
        <a href="#" class="rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-semibold hover:bg-soft">Instagram</a>
        <a href="#" class="rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-semibold hover:bg-soft">TikTok</a>
    </div>
</section>

{{-- REVIEWS --}}
<section class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-accent">Reviews</p>
        <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">O que os clientes dizem</h2>
    </div>
    <div class="grid gap-5 md:grid-cols-3">
        @foreach ($reviews as [$author, $city, $quote])
            <figure class="reveal rounded-[2rem] border border-line bg-surface p-7">
                <div class="text-accent">★★★★★</div>
                <blockquote class="mt-4 text-lg leading-relaxed">“{{ $quote }}”</blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-full bg-brand font-bold text-brandink">{{ mb_substr($author, 0, 1) }}</span>
                    <span><b class="block text-sm">{{ $author }}</b><span class="text-xs text-mute">{{ $city }}</span></span>
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>

{{-- FAQ --}}
<section id="faq" class="mx-auto max-w-3xl px-5 pb-24">
    <div class="reveal mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-widest text-accent">Suporte</p>
        <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">Perguntas frequentes</h2>
        <p class="mt-3 text-mute">Não encontrou a resposta? Fale connosco por WhatsApp.</p>
    </div>
    <div class="space-y-3">
        @foreach ($faqs as [$question, $answer])
            <details class="group reveal rounded-3xl border border-line bg-surface px-6 py-5">
                <summary class="flex cursor-pointer items-center justify-between gap-4 font-semibold">{{ $question }}<span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-soft text-lg transition group-open:rotate-45">+</span></summary>
                <p class="mt-3 text-mute">{{ $answer }}</p>
            </details>
        @endforeach
    </div>
</section>

{{-- NEWSLETTER / CONTACTO --}}
<section id="contacto" class="mx-auto max-w-6xl px-5 pb-24">
    <div class="reveal glass grid grid-cols-12 items-center gap-y-8 rounded-[2.5rem] p-6 sm:p-8 md:gap-x-8 md:p-14">

        {{-- Texto --}}
        <div class="col-span-12 md:col-span-6">
            <h2 class="font-display text-2xl font-extrabold leading-tight md:text-3xl">Receitas e novidades no seu email</h2>
            <p class="mt-3 text-sm text-mute">Sem spam. Só dicas, promoções e produtos novos.</p>
        </div>

        {{-- Formulário --}}
        <div class="col-span-12 md:col-span-6">
            <div class="grid grid-cols-12 gap-y-2 lg:gap-x-2">
                <input id="email" type="email" placeholder="o.seu@email.com"
                    class="col-span-12 rounded-full border border-line bg-surface px-5 py-3.5 
                    outline-none placeholder:text-mute focus:border-accent focus:ring-0 lg:col-span-8">
                <button id="sub" type="button"
                    class="col-span-12 rounded-full bg-brand px-6 py-3.5 text-sm font-semibold 
                    text-brandink transition hover:-translate-y-0.5 lg:col-span-4">
                    Subscrever
                </button>
            </div>
            <p id="msg" class="mt-2 min-h-5 pl-4 text-sm text-mute"></p>
        </div>

    </div>
</section>

@endsection