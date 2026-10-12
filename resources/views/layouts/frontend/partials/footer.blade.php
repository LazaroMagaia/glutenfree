<footer class="border-t border-line bg-surface">
    <div class="mx-auto grid max-w-6xl gap-8 px-5 py-12 md:grid-cols-3">
        <div>
            <div class="flex items-center gap-2 font-display text-lg font-extrabold">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="h-10 w-10 rounded-full">Gluten Free
            </div>
            <p class="mt-3 max-w-xs text-sm text-mute">Alimentação segura e saborosa para quem vive sem glúten em Moçambique.</p>
        </div>

        <div class="text-sm">
            <p class="mb-3 font-semibold">Navegar</p>
            <div class="flex flex-col gap-2 text-mute">
                <a href="{{ url('/') }}#categorias">Categorias</a>
                <a href="{{ url('/') }}#produtos">Produtos</a>
                <a href="{{ url('/') }}#receitas">Receitas</a>
            </div>
        </div>

        <div class="text-sm">
            <p class="mb-3 font-semibold">Contacto</p>
            <div class="flex flex-col gap-2 text-mute">
                <span>WhatsApp: +258 84 000 0000</span>
                <span>info@glutenfree.co.mz</span>
                <span>Maputo, Moçambique</span>
            </div>
        </div>
    </div>

    <p class="border-t border-line py-5 text-center text-xs text-mute">© {{ date('Y') }} 
        Gluten Free. Todos os direitos reservados.</p>
</footer>
