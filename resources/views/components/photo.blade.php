@props(['id', 'emoji' => '', 'alt' => '', 'w' => 800])

{{-- Foto do Unsplash (placeholder). Se falhar, mostra o emoji. Trocar por asset('storage/...') mais tarde. --}}
<div {{ $attributes->merge(['class' => 'ph']) }}>
    <span>{{ $emoji }}</span>
    <img src="https://images.unsplash.com/photo-{{ $id }}?auto=format&amp;fit=crop&amp;w={{ $w }}&amp;q=70" alt="{{ $alt }}" loading="lazy" onerror="this.remove()">
    {{ $slot }}
</div>
