<?php

/*
|--------------------------------------------------------------------------
| Definição das configurações da loja
|--------------------------------------------------------------------------
| Aqui ficam os inputs padrão. O admin só preenche os valores na página.
| Para criar um input novo, acrescenta uma entrada em 'fields'.
| Tipos: text | textarea | email | number | boolean
*/

return [

    'groups' => [
        'store' => [
            'label'       => 'Dados da loja',
            'description' => 'Informações de contacto e estado da loja.',
            'icon'        => 'store',
        ],
        'orders' => [
            'label'       => 'Encomendas e stock',
            'description' => 'Numeração das encomendas, reservas e alertas.',
            'icon'        => 'shopping-bag',
        ],
        'delivery' => [
            'label'       => 'Levantamento',
            'description' => 'Opção de levantar a encomenda na loja.',
            'icon'        => 'map-pin',
        ],
        'payments' => [
            'label'       => 'Pagamentos',
            'description' => 'Métodos aceites e instruções para o cliente.',
            'icon'        => 'credit-card',
        ],
    ],

    'fields' => [

        // ---------- Dados da loja ----------
        'store_name' => [
            'group'   => 'store',
            'label'   => 'Nome da loja',
            'type'    => 'text',
            'default' => 'Gluten Free',
            'rules'   => ['required', 'string', 'max:100'],
        ],
        'store_phone' => [
            'group'   => 'store',
            'label'   => 'Telefone',
            'type'    => 'text',
            'default' => null,
            'rules'   => ['nullable', 'string', 'max:20'],
        ],
        'store_whatsapp' => [
            'group'   => 'store',
            'label'   => 'WhatsApp',
            'type'    => 'text',
            'default' => null,
            'help'    => 'Número usado nos contactos com clientes.',
            'rules'   => ['nullable', 'string', 'max:20'],
        ],
        'store_email' => [
            'group'   => 'store',
            'label'   => 'Email da loja',
            'type'    => 'email',
            'default' => null,
            'rules'   => ['nullable', 'email', 'max:255'],
        ],
        'store_address' => [
            'group'   => 'store',
            'label'   => 'Morada',
            'type'    => 'textarea',
            'default' => null,
            'rules'   => ['nullable', 'string', 'max:500'],
        ],
        'store_open' => [
            'group'   => 'store',
            'label'   => 'Loja aberta para encomendas',
            'type'    => 'boolean',
            'default' => true,
            'help'    => 'Desligado, o website deixa de aceitar novas encomendas.',
            'rules'   => ['boolean'],
        ],

        // ---------- Encomendas e stock ----------
        'order_number_prefix' => [
            'group'   => 'orders',
            'label'   => 'Prefixo do número da encomenda',
            'type'    => 'text',
            'default' => 'GF',
            'help'    => 'Ex.: GF gera GF-2026-000123. Só letras e números, até 5 caracteres.',
            'rules'   => ['required', 'alpha_num', 'max:5'],
        ],
        'reservation_minutes' => [
            'group'   => 'orders',
            'label'   => 'Tempo de reserva do stock (minutos)',
            'type'    => 'number',
            'default' => 60,
            'help'    => 'Encomendas por pagar libertam o stock reservado depois deste tempo.',
            'rules'   => ['required', 'integer', 'min:5', 'max:1440'],
        ],
        'low_stock_default' => [
            'group'   => 'orders',
            'label'   => 'Alerta de stock baixo (por defeito)',
            'type'    => 'number',
            'default' => 5,
            'help'    => 'Usado nas variantes que não tenham um valor próprio. 0 desliga o alerta.',
            'rules'   => ['required', 'integer', 'min:0', 'max:100000'],
        ],

        // ---------- Levantamento ----------
        'pickup_enabled' => [
            'group'   => 'delivery',
            'label'   => 'Permitir levantamento na loja',
            'type'    => 'boolean',
            'default' => true,
            'rules'   => ['boolean'],
        ],
        'pickup_address' => [
            'group'   => 'delivery',
            'label'   => 'Local de levantamento',
            'type'    => 'textarea',
            'default' => null,
            'help'    => 'Morada e horário que o cliente vê ao escolher levantamento.',
            'rules'   => ['nullable', 'string', 'max:500'],
        ],

        // ---------- Pagamentos ----------
        'cod_enabled' => [
            'group'   => 'payments',
            'label'   => 'Aceitar pagamento na entrega',
            'type'    => 'boolean',
            'default' => true,
            'rules'   => ['boolean'],
        ],
        'bank_transfer_details' => [
            'group'   => 'payments',
            'label'   => 'Dados para transferência bancária',
            'type'    => 'textarea',
            'default' => null,
            'help'    => 'Banco, NIB e titular. Se ficar vazio, a transferência não é oferecida.',
            'rules'   => ['nullable', 'string', 'max:1000'],
        ],
        'payment_instructions' => [
            'group'   => 'payments',
            'label'   => 'Instruções de pagamento',
            'type'    => 'textarea',
            'default' => null,
            'help'    => 'Texto mostrado ao cliente depois de fazer a encomenda.',
            'rules'   => ['nullable', 'string', 'max:1000'],
        ],
    ],
];