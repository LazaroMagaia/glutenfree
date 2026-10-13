@extends('layouts.backend')

@section('title', 'Gluten Free · Configurações')

@section('content')
    @include('components.flash')

<section class="mx-auto max-w-5xl space-y-6">

    {{-- Cabeçalho --}}
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-ink sm:text-3xl">
            Configurações
        </h1>

        <p class="mt-2 text-sm text-mute">
            Define os dados e as regras principais da loja.
        </p>
    </div>

    {{-- Erros de validação --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-500/30 bg-red-500/5 p-4 text-sm text-red-600">
            <div class="flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                class="lucide lucide-circle-alert preview-icon"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                Verifica os campos do formulário.
            </div>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        @foreach ($sections as $groupKey => $section)
            <div class="overflow-hidden rounded-2xl border border-line bg-surface">
                <div class="border-b border-line p-5 sm:p-6">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-soft text-ink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings preview-icon"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>

                        <div>
                            <h2 class="font-bold text-ink">{{ $section['label'] }}</h2>
                            <p class="mt-1 text-xs text-mute">{{ $section['description'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                    @foreach ($section['fields'] as $key => $field)
                        @php
                            $type = $field['type'];
                            $value = old($key, $field['value']);
                            $required = in_array('required', $field['rules'], true);
                            $inputClass = 'w-full rounded-xl border-line bg-bg px-4 py-3 text-sm text-ink placeholder:text-mute focus:border-brand
                            focus:ring-brand';
                        @endphp

                        @if ($type === 'boolean')
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between gap-4 rounded-xl border border-line bg-bg p-4">
                                    <div class="min-w-0">
                                        <label for="{{ $key }}" class="block cursor-pointer text-sm font-semibold text-ink">
                                            {{ $field['label'] }}
                                        </label>

                                        @isset($field['help'])
                                            <p class="mt-1 text-xs text-mute">{{ $field['help'] }}</p>
                                        @endisset
                                    </div>

                                    <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                                        <input type="hidden" name="{{ $key }}" value="0">
                                        <input id="{{ $key }}"
                                               name="{{ $key }}"
                                               type="checkbox"
                                               value="1"
                                               class="peer sr-only"
                                               @checked($value)>
                                        <span class="h-6 w-11 rounded-full border border-line bg-soft transition
                                                     peer-checked:border-brand peer-checked:bg-brand
                                                     peer-focus-visible:ring-2 peer-focus-visible:ring-brand
                                                     after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full
                                                     after:bg-mute after:transition peer-checked:after:translate-x-5 peer-checked:after:bg-brandink"></span>
                                    </label>
                                </div>

                                @error($key)
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="{{ $type === 'textarea' ? 'sm:col-span-2' : '' }}">
                                <label for="{{ $key }}" class="mb-2 block text-sm font-semibold text-ink">
                                    {{ $field['label'] }}
                                    @if ($required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if ($type === 'textarea')
                                    <textarea id="{{ $key }}"
                                              name="{{ $key }}"
                                              rows="3"
                                              class="{{ $inputClass }} @error($key) border-red-500 @enderror">{{ $value }}</textarea>
                                @else
                                    <input id="{{ $key }}"
                                           name="{{ $key }}"
                                           type="{{ $type === 'number' ? 'number' : ($type === 'email' ? 'email' : 'text') }}"
                                           value="{{ $value }}"
                                           @if ($type === 'number') inputmode="numeric" min="0" @endif
                                           @if ($required) required @endif
                                           class="{{ $inputClass }} @error($key) border-red-500 @enderror">
                                @endif

                                @isset($field['help'])
                                    <p class="mt-1.5 text-xs text-mute">{{ $field['help'] }}</p>
                                @endisset

                                @error($key)
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Acções --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-brandink shadow-sm transition hover:opacity-90">
                Guardar configurações
            </button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }
});
</script>
@endpush
