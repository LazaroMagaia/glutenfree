@extends('layouts.auth')

@section('title', 'Entrar · Gluten Free')

@php
    $input = 'w-full rounded-full border border-line bg-surface px-5 py-3.5 text-sm outline-none placeholder:text-mute focus:border-accent focus:ring-0';
@endphp

@section('content')

<h1 class="font-display text-3xl font-extrabold">Entrar</h1>
<p class="mt-1 text-sm text-mute">Use o seu telefone ou email para aceder à conta.</p>

@if (session('status'))
    <p class="mt-4 rounded-2xl bg-soft px-4 py-3 text-sm">{{ session('status') }}</p>
@endif

<form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
    @csrf

    <div>
        <label for="login" class="mb-1.5 block pl-2 text-sm font-semibold">Telefone ou email</label>
        <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="84 123 4567 ou o.seu@email.com" class="{{ $input }}">
        @error('login') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1.5 block pl-2 text-sm font-semibold">Palavra-passe</label>
        <div class="relative">
            <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••" class="{{ $input }} pr-12">
            <button type="button" data-pass aria-label="Mostrar palavra-passe" class="absolute right-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full text-mute hover:bg-soft">
                <svg class="pointer-events-none h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12zM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
            </button>
        </div>
        @error('password') <p class="mt-1.5 pl-2 text-sm text-red-500">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2 px-1 text-sm">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="remember" class="rounded border-line bg-surface text-brand focus:ring-0 focus:ring-offset-0">
            <span class="text-mute">Manter sessão</span>
        </label>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="font-semibold text-brand hover:underline">Esqueci a palavra-passe</a>
        @endif
    </div>

    <button type="submit" class="w-full rounded-full bg-brand px-6 py-3.5 text-sm font-semibold text-brandink transition hover:-translate-y-0.5">Entrar</button>
</form>

<p class="mt-6 text-center text-sm text-mute">Ainda não tem conta? <a href="{{ route('register') }}" class="font-semibold text-brand hover:underline">Criar conta</a></p>

@endsection