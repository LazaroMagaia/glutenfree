@props([
    'user',
    'size' => 'md',
    'rounded' => 'rounded-full',
])

@php
    $sizes = [
        'xs' => 'h-7 w-7 text-xs',
        'sm' => 'h-9 w-9 text-sm',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-16 w-16 text-xl',
        'xl' => 'h-20 w-20 text-2xl',
    ];

    $box = $sizes[$size] ?? $sizes['md'];
    $letter = mb_strtoupper(mb_substr(trim($user->name ?? ''), 0, 1)) ?: '?';
@endphp

@if ($user->avatar_url)
    <img src="{{ $user->avatar_url }}"
         alt="Foto de {{ $user->name }}"
         loading="lazy"
         {{ $attributes->merge(['class' => "$box $rounded shrink-0 object-cover"]) }}>
@else
    <span aria-hidden="true"
          {{ $attributes->merge(['class' => "$box $rounded flex shrink-0 items-center justify-center bg-soft font-extrabold text-ink"]) }}>
        {{ $letter }}
    </span>
@endif