@extends('layouts.auth')

@section('title', 'Criar conta · Gluten Free')

@php
    $input = 'w-full rounded-full border border-line bg-surface px-5 py-3.5 text-sm outline-none placeholder:text-mute focus:border-accent focus:ring-0';
    $eye = 'M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12zM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z';
@endphp

@section('content')

<h1 class="font-display text-3xl font-extrabold">Criar conta</h1>
<p class="mt-1 text-sm text-mute">Compre mais depressa e acompanhe as suas encomendas.</p>

<form method="POST" action="{{ route('register') }}" class="mt-6 grid grid-cols-12 gap-y-4 sm:gap-x-4">
    @csrf

    {{-- Nome --}}
    <div class="col-span-12">
        <label for="name" class="mb-1.5 block pl-2 text-sm font-semibold">Nome</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="O seu nome" class="{{ $input }}">
        @error('name') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Telefone --}}
    <div class="col-span-12 sm:col-span-6">
        <label for="phone" class="mb-1.5 block pl-2 text-sm font-semibold">Telefone</label>
        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" placeholder="84 123 4567" class="{{ $input }}">
        @error('phone') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Email (opcional) --}}
    <div class="col-span-12 sm:col-span-6">
        <label for="email" class="mb-1.5 block pl-2 text-sm font-semibold">Email <span class="font-normal text-mute">(opcional)</span></label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="o.seu@email.com" class="{{ $input }}">
        @error('email') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Palavra-passe --}}
    <div class="col-span-12 sm:col-span-6">
        <label for="password" class="mb-1.5 block pl-2 text-sm font-semibold">Palavra-passe</label>
        <div class="relative">
            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Mín. 8 caracteres" class="{{ $input }} pr-12">
            <button type="button" data-pass aria-label="Mostrar palavra-passe" class="absolute right-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full text-mute hover:bg-soft">
                <svg class="pointer-events-none h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $eye }}"/></svg>
            </button>
        </div>
        @error('password') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Confirmar --}}
    <div class="col-span-12 sm:col-span-6">
        <label for="password_confirmation" class="mb-1.5 block pl-2 text-sm font-semibold">Confirmar palavra-passe</label>
        <div class="relative">
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Repita a palavra-passe" class="{{ $input }} pr-12">
            <button type="button" data-pass aria-label="Mostrar palavra-passe" class="absolute right-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full text-mute hover:bg-soft">
                <svg class="pointer-events-none h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $eye }}"/></svg>
            </button>
        </div>
    </div>

    <div class="col-span-12">
        <button type="submit" class="w-full rounded-full bg-brand px-6 py-3.5 text-sm font-semibold text-brandink transition hover:-translate-y-0.5">Criar conta</button>
    </div>
</form>

<p class="mt-6 text-center text-sm text-mute">Já tem conta? <a href="{{ route('login') }}" class="font-semibold text-brand hover:underline">Entrar</a></p>

@endsection