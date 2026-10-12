@extends('layouts.backend')

@section('title', 'Gluten Free · A minha conta')

@php
    $u = auth()->user();
    $firstName = explode(' ', trim($u->name))[0];

    // Dados de exemplo — substituir por encomendas reais do utilizador
    $orders = [
        ['#1048', 640,  'Pago',     'há 12 min'],
        ['#1031', 1180, 'Enviado',  'há 2 semanas'],
        ['#1012', 320,  'Pendente', 'há 1 mês'],
    ];
    $cls = ['Pago' => 'b-ok', 'Enviado' => 'b-info', 'Pendente' => 'b-warn', 'Cancelado' => 'b-bad'];
@endphp

@section('content')

<div class="rise flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold sm:text-4xl">Olá, {{ $firstName }} 👋</h1>
        <p class="mt-1 text-mute">Bem-vindo à sua área de cliente.</p>
    </div>
    <a href="{{ url('/') }}#produtos" class="rounded-full bg-brand px-5 py-2.5 text-sm font-semibold text-brandink">Continuar a comprar</a>
</div>

<section class="grid gap-4 xl:grid-cols-3">
    <div class="card rise p-6">
        <h2 class="text-lg font-extrabold">A minha conta</h2>
        <dl class="mt-5 space-y-4 text-sm">
            <div><dt class="text-mute">Nome</dt><dd class="font-semibold">{{ $u->name }}</dd></div>
            <div><dt class="text-mute">Email</dt><dd class="font-semibold">{{ $u->email ?? '—' }}</dd></div>
            <div><dt class="text-mute">Telefone</dt><dd class="font-semibold">{{ $u->phone }}</dd></div>
        </dl>
    </div>

    <div class="card rise p-6 xl:col-span-2">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-extrabold">As minhas encomendas</h2>
            <a href="#" class="text-sm font-semibold text-brand hover:underline">Ver todas →</a>
        </div>
        <div class="noscroll mt-4 overflow-x-auto">
            <table class="w-full min-w-[420px] text-left text-sm">
                <thead class="text-xs uppercase tracking-wider text-mute">
                    <tr><th class="pb-3 font-semibold">Pedido</th><th class="pb-3 font-semibold">Total</th><th class="pb-3 font-semibold">Estado</th><th class="pb-3 text-right font-semibold">Quando</th></tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($orders as [$id, $total, $status, $when])
                        <tr class="hover:bg-soft/50">
                            <td class="py-3.5 font-bold">{{ $id }}</td>
                            <td class="py-3.5 font-semibold">{{ number_format($total, 0, ',', ' ') }} MZN</td>
                            <td class="py-3.5"><span class="badge {{ $cls[$status] }}">{{ $status }}</span></td>
                            <td class="py-3.5 text-right text-mute">{{ $when }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
