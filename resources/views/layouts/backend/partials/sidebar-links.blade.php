@foreach ($groups as [$title, $items])
    <div>
        <p class="mb-2 px-3 text-[11px] font-bold uppercase
                  tracking-widest text-mute">
            {{ $title }}
        </p>

        <div class="space-y-1">
            @foreach ($items as [$icon, $label, $route])
                <a href="{{ $route ? route($route) : '#' }}"
                   class="nav-a {{ $route && request()->routeIs($route) ? 'on' : '' }}">

                    @include('layouts.backend.partials.icon', ['n' => $icon])

                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
@endforeach