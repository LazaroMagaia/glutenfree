@php
    $groups = [
        ['Conta', [
            ['home', 'Início', 'user.index'],
            ['bag', 'As minhas encomendas', null],
            ['users', 'O meu perfil', null],
        ]],
    ];
@endphp

@include('layouts.backend.partials.sidebar-links', ['groups' => $groups])