@extends('layouts.backend')

@section('title', 'Gluten Free · Dashboard')

@php
    // Dados de exemplo — substituir por dados do controller / BD
    $stats = [
        ['Vendas hoje',    '12 450 MZN', 8.2, 'bag'],
        ['Encomendas',     '38',         12,  'box'],
        ['Novos clientes', '14',         4.1, 'users'],
        ['Ticket médio',   '328 MZN',    -2,  'star'],
    ];
    $topProducts = [['Farinha de arroz', 124], ['Pão de forma', 98], ['Massa penne', 76], ['Biscoitos de coco', 54]];
    $orders = [
        ['#1048', 'Ana Machava',    'Maputo',  640,  'Pago',      'há 12 min'],
        ['#1047', 'Carlos Sitoe',   'Matola',  1180, 'Enviado',   'há 1 h'],
        ['#1046', 'Marta Jossias',  'Beira',   320,  'Pendente',  'há 2 h'],
        ['#1045', 'Hélio Cumbe',    'Maputo',  905,  'Pago',      'há 3 h'],
        ['#1044', 'Sónia Tembe',    'Xai-Xai', 260,  'Cancelado', 'ontem'],
        ['#1043', 'Paulo Mondlane', 'Maputo',  1450, 'Enviado',   'ontem'],
    ];
    $stock = [['Farinha de mandioca', 5, 20], ['Pão de forma', 6, 30], ['Biscoitos de coco', 8, 25]];
    $feed = [
        ['Nova encomenda #1048 de Ana Machava', '12 min'],
        ['Pagamento M-Pesa confirmado (#1045)', '3 h'],
        ['Nova review 5★ em Farinha de arroz',  '5 h'],
        ['14 novas subscrições na newsletter',  'hoje'],
    ];
    $cls = ['Pago' => 'b-ok', 'Enviado' => 'b-info', 'Pendente' => 'b-warn', 'Cancelado' => 'b-bad'];
    $series = [
        7  => [8200, 9400, 7800, 11200, 10400, 13800, 12450],
        30 => collect(range(0, 29))->map(fn ($i) => (int) round(7000 + sin($i / 3) * 2500 + $i * 140 + (($i * 37) % 11) * 220))->all(),
    ];
    $maxTop    = $topProducts[0][1];
    $firstName = explode(' ', trim(auth()->user()->name))[0];
@endphp

@section('content')

<div class="rise flex flex-wrap items-end justify-between gap-4">
    <div>
        <p id="today" class="text-sm text-mute"></p>
        <h1 class="text-3xl font-extrabold sm:text-4xl">Olá, {{ $firstName }} 👋</h1>
        <p class="mt-1 text-mute">Resumo da sua loja hoje.</p>
    </div>
    <div class="flex gap-2">
        <button type="button" class="rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-semibold hover:bg-soft">Exportar</button>
        <button type="button" class="flex items-center gap-2 rounded-full bg-brand px-5 py-2.5 text-sm font-semibold text-brandink">@include('layouts.backend.partials.icon', ['n' => 'plus', 's' => 16])Novo produto</button>
    </div>
</div>

{{-- STATS --}}
<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($stats as $k => [$label, $value, $delta, $icon])
        <div class="card rise p-6" style="animation-delay:{{ $k * 70 }}ms">
            <div class="flex items-center justify-between">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-soft text-brand">@include('layouts.backend.partials.icon', ['n' => $icon])</span>
                <span class="badge {{ $delta >= 0 ? 'b-ok' : 'b-bad' }}">{{ $delta >= 0 ? '▲' : '▼' }} {{ abs($delta) }}%</span>
            </div>
            <p class="mt-5 text-sm text-mute">{{ $label }}</p>
            <p class="mt-1 text-3xl font-extrabold">{{ $value }}</p>
        </div>
    @endforeach
</section>

{{-- GRÁFICO + MAIS VENDIDOS --}}
<section class="grid gap-4 xl:grid-cols-3">
    <div class="card rise p-6 xl:col-span-2">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><h2 class="text-lg font-extrabold">Vendas</h2><p class="text-sm text-mute">Receita por dia (MZN)</p></div>
            <div class="flex rounded-full bg-soft p-1 text-sm font-semibold">
                <button type="button" data-r="7" class="range rounded-full px-4 py-1.5">7 dias</button>
                <button type="button" data-r="30" class="range rounded-full px-4 py-1.5">30 dias</button>
            </div>
        </div>
        <div id="chart" class="mt-4"></div>
    </div>

    <div class="card rise p-6">
        <h2 class="text-lg font-extrabold">Mais vendidos</h2><p class="text-sm text-mute">Este mês</p>
        <div class="mt-5 space-y-5">
            @foreach ($topProducts as [$name, $qty])
                <div>
                    <div class="flex justify-between text-sm"><b>{{ $name }}</b><span class="text-mute">{{ $qty }} un.</span></div>
                    <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-soft"><div class="h-full rounded-full bg-brand" style="width:{{ $qty / $maxTop * 100 }}%"></div></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ENCOMENDAS + LATERAL --}}
<section class="grid gap-4 xl:grid-cols-3">
    <div class="card rise p-6 xl:col-span-2">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-extrabold">Encomendas recentes</h2>
            <a href="#" class="text-sm font-semibold text-brand hover:underline">Ver todas →</a>
        </div>
        <div class="noscroll mt-4 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="text-xs uppercase tracking-wider text-mute">
                    <tr><th class="pb-3 font-semibold">Pedido</th><th class="pb-3 font-semibold">Cliente</th><th class="pb-3 font-semibold">Total</th><th class="pb-3 font-semibold">Estado</th><th class="pb-3 text-right font-semibold">Quando</th></tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($orders as [$id, $client, $city, $total, $status, $when])
                        <tr class="hover:bg-soft/50">
                            <td class="py-3.5 font-bold">{{ $id }}</td>
                            <td class="py-3.5">{{ $client }}<span class="block text-xs text-mute">{{ $city }}</span></td>
                            <td class="py-3.5 font-semibold">{{ number_format($total, 0, ',', ' ') }} MZN</td>
                            <td class="py-3.5"><span class="badge {{ $cls[$status] }}">{{ $status }}</span></td>
                            <td class="py-3.5 text-right text-mute">{{ $when }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <div class="card rise p-6">
            <div class="flex items-center justify-between"><h2 class="text-lg font-extrabold">Stock baixo</h2><span class="badge b-bad">{{ count($stock) }} alertas</span></div>
            <div class="mt-4 space-y-4">
                @foreach ($stock as [$name, $qty, $min])
                    <div>
                        <div class="flex justify-between text-sm"><b>{{ $name }}</b><span class="text-bad">{{ $qty }} em stock</span></div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-soft"><div class="h-full rounded-full bg-bad" style="width:{{ $qty / $min * 100 }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card rise p-6">
            <h2 class="text-lg font-extrabold">Atividade</h2>
            <ul class="mt-4 space-y-4">
                @foreach ($feed as [$text, $when])
                    <li class="flex gap-3 text-sm"><i class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full bg-accent"></i><div><p>{{ $text }}</p><p class="text-xs text-mute">{{ $when }}</p></div></li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
const series = @json($series);
const mz = v => v.toLocaleString('pt-MZ') + ' MZN';

document.getElementById('today').textContent =
    new Date().toLocaleDateString('pt-MZ', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });

function chart(n) {
    const d = series[n], W = 700, H = 260, pl = 44, pr = 10, pt = 14, pb = 28, mx = Math.max(...d) * 1.1;
    const x = i => pl + i * (W - pl - pr) / (d.length - 1), y = v => pt + (1 - v / mx) * (H - pt - pb);
    const line = d.map((v, i) => (i ? 'L' : 'M') + x(i).toFixed(1) + ' ' + y(v).toFixed(1)).join(' ');
    const grid = [0, .25, .5, .75, 1].map(t => {
        const v = mx * t, yy = y(v);
        return `<line x1="${pl}" x2="${W - pr}" y1="${yy}" y2="${yy}" stroke="var(--line)" stroke-dasharray="4 4"/><text x="${pl - 8}" y="${yy + 4}" text-anchor="end" font-size="11" fill="var(--mute)">${Math.round(v / 1000)}k</text>`;
    }).join('');
    const step = n === 7 ? 1 : 5;
    const xl = d.map((_, i) => i % step === 0 || i === d.length - 1
        ? `<text x="${x(i)}" y="${H - 8}" text-anchor="middle" font-size="11" fill="var(--mute)">${n === 7 ? ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'][i] : i + 1}</text>` : '').join('');
    const dots = d.map((v, i) => `<circle cx="${x(i)}" cy="${y(v)}" r="${n === 7 ? 5 : 3}" fill="var(--surface)" stroke="var(--brand)" stroke-width="2.5"><title>${mz(v)}</title></circle>`).join('');

    document.getElementById('chart').innerHTML =
        `<svg viewBox="0 0 ${W} ${H}" class="w-full" role="img" aria-label="Gráfico de vendas"><defs><linearGradient id="g" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="var(--accent)" stop-opacity=".45"/><stop offset="1" stop-color="var(--accent)" stop-opacity="0"/></linearGradient></defs>${grid}${xl}<path d="${line} L${x(d.length - 1)} ${H - pb} L${x(0)} ${H - pb}Z" fill="url(#g)"/><path d="${line}" fill="none" stroke="var(--brand)" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>${dots}</svg>`;

    document.querySelectorAll('.range').forEach(b => {
        const on = +b.dataset.r === n;
        b.style.background = on ? 'var(--brand)' : 'transparent';
        b.style.color = on ? 'var(--brandink)' : 'var(--mute)';
    });
}
document.querySelectorAll('.range').forEach(b => b.addEventListener('click', () => chart(+b.dataset.r)));
chart(7);
</script>
@endpush
