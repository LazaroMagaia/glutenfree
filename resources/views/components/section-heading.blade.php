@props(['eyebrow', 'title', 'sub' => null, 'center' => true])

<div class="reveal mb-12 {{ $center ? 'mx-auto max-w-2xl text-center' : 'max-w-xl' }}">
    <p class="text-sm font-semibold uppercase tracking-widest text-accent">{{ $eyebrow }}</p>
    <h2 class="mt-2 font-display text-4xl font-extrabold sm:text-5xl">{{ $title }}</h2>
    @if ($sub)<p class="mt-3 text-mute">{{ $sub }}</p>@endif
</div>
