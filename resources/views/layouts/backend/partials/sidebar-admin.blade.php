@php
    $groups = [
        ['Principal', [
            ['home', 'Dashboard', 'admin.index'],
            ['bag', 'Encomendas', null],
            ['box', 'Produtos', null],
            ['users', 'Gestão de utilizadores', 'admin.users.index'],
        ]],
        ['Conteúdo', [
            ['book', 'Receitas', null],
            ['star', 'Reviews', null],
            ['help', 'FAQ', null],
        ]],
        ['Sistema', [
            ['sliders', 'Definições', null],
        ]],
    ];
@endphp

@include('layouts.backend.partials.sidebar-links', ['groups' => $groups])