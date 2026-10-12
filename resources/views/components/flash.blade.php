@if (session('success'))
    <div role="alert" class="flex items-start gap-3 rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600">
        <i data-lucide="check-circle-2" class="mt-0.5 h-4 w-4 shrink-0"></i>
        <p class="font-medium">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div role="alert" class="flex items-start gap-3 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-500">
        <i data-lucide="alert-circle" class="mt-0.5 h-4 w-4 shrink-0"></i>
        <p class="font-medium">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-500">
        <p class="font-bold">Corrige os seguintes erros:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif