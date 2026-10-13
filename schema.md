# Gluten Free — Esquema da base de dados (Fase 1)

Siga este documento de cima para baixo. Cada migration já está escrita e ordenada por dependências.

## 1. Convenções (valem para tudo)

| Regra | Detalhe |
|---|---|
| Dinheiro | `unsignedBigInteger` em **centavos de MZN** (150,00 MZN = `15000`). Nunca `float`. |
| Estados | Colunas `string` + Enum PHP em `app/Enums`. Os valores da coluna são os `value` do enum. |
| Apagar | Produtos, variantes, categorias, zonas e utilizadores usam `softDeletes`. Encomendas e pagamentos **nunca** se apagam. |
| Snapshot | `order_items` e os campos `ship_*`/`customer_*` da encomenda copiam os dados no momento da compra. |
| Livro-razão | `stock_movements` e `order_status_histories` só recebem linhas novas. Nunca se editam. |
| Telefone | Guardar sempre com `User::normalizePhone()` (formato `+258...`). |
| Chaves | Ao apagar o pai: `cascade` só em dados que não têm sentido sem ele (imagens, itens); `restrict` onde há histórico financeiro ou de stock; `nullOnDelete` em referências opcionais. |

## 2. Ordem das migrations

Copie a pasta `migrations/` para `database/migrations/` e corra `php artisan migrate`. A ordem já respeita as chaves estrangeiras.

| # | Tabela | Depende de | Para quê |
|---|---|---|---|
| 01 | `users` (alteração) | — | `is_active` e `softDeletes` |
| 02 | `settings` | — | Configurações essenciais (chave/valor) |
| 03 | `categories` | — | Categorias (já com `parent_id` para subcategorias) |
| 04 | `products` | categories | Dados do produto e informação "sem glúten" |
| 05 | `product_variants` | products | SKU, preço, promoção e contadores de stock |
| 06 | `product_images` | products | Fotografias e galeria |
| 07 | `stock_movements` | product_variants, users | Histórico de stock |
| 08 | `delivery_zones` | — | Zonas e taxas de entrega |
| 09 | `couriers` | users | Entregadores e transportadoras |
| 10 | `addresses` | users | Moradas guardadas do cliente |
| 11 | `orders` | users, delivery_zones | Encomendas |
| 12 | `order_items` | orders, products, product_variants | Itens (com snapshot) |
| 13 | `order_status_histories` | orders, users | Histórico de estados |
| 14 | `payments` | orders, users | Pagamentos |
| 15 | `refunds` | payments, orders, users | Reembolsos |
| 16 | `payment_webhook_events` | — | Evita processar o mesmo webhook duas vezes |
| 17 | `deliveries` | orders, couriers | Entregas |

No `User`, adicione `use SoftDeletes;` (de `Illuminate\Database\Eloquent\SoftDeletes`). Como o `unique` de `email` e `phone` continua a valer para contas apagadas, esses dados não podem ser reutilizados por outra conta.

## 3. Onde fica cada requisito do roadmap

**Produtos**

| Requisito | Onde |
|---|---|
| Nome, descrição | `products.name`, `short_description`, `description` |
| Preço e preço promocional | `product_variants.price`, `sale_price` |
| SKU / código interno | `product_variants.sku` |
| Peso ou embalagem | `product_variants.name` e `weight_grams` |
| Fotografias e galeria | `product_images` (`is_primary` marca a principal) |
| Categoria | `products.category_id` |
| Ingredientes, nutrição, alergénios | `ingredients`, `nutrition` (JSON), `allergens` |
| Aviso "sem glúten" | `cross_contamination_notice`, `gluten_free_certification` |
| Conservação e preparação | `storage_info`, `preparation_info` |
| Rascunho / publicado / inactivo | `status` |
| Destaque, novo, favorito | `is_featured`, `is_new`, `is_favorite` |
| Disponível para venda | `is_purchasable` |
| Arquivar | `softDeletes` |

Regra: **todo o produto tem pelo menos uma variante**. Produto simples = uma variante chamada "Unidade". O preço vive sempre na variante.

**Categorias:** `name`, `description`, `image`, `sort_order` (ordem), `is_active` (activar/desactivar).

**Stock**

| Requisito | Onde |
|---|---|
| Quantidade disponível | `quantity_on_hand - quantity_reserved` (calculado) |
| Reservado | `quantity_reserved` |
| Indisponível | `quantity_unavailable` |
| Stock mínimo e alerta | `low_stock_threshold` (0 = sem alerta) |
| Entradas, saídas, histórico | `stock_movements` |
| Ajuste com justificação | `stock_movements.reason` (obrigatório) |
| Perdas e danos | tipos `loss` e `damage` |
| Lotes e validade | **Fase 2** (acrescentar `batch_id` aos movimentos) |

**Encomendas, pagamentos e entregas**

| Requisito | Onde |
|---|---|
| Número, data, cliente | `orders.number`, `placed_at`, `customer_*` |
| Produtos, quantidades, preços | `order_items` |
| Subtotal, entrega, total | `subtotal`, `shipping_total`, `grand_total` |
| Morada e instruções | `ship_*`, `ship_reference`, `customer_notes` |
| Estado do pagamento / da encomenda | `payment_status` / `status` |
| Histórico, notas internas | `order_status_histories`, `internal_notes` |
| Cancelamento | `cancel_reason`, `cancelled_at` |
| Reembolso | `refunds` |
| Referência da transação | `payments.provider_reference` |
| Zonas e taxas | `delivery_zones` |
| Entregador, histórico | `deliveries.courier_id`, `order_status_histories` |
| Entrega falhada | `deliveries.status = failed`, `attempts`, `failure_reason` |
| Dinheiro recebido na entrega | `deliveries.cod_collected`, `cod_settled_at` |

## 4. Estados e transições

**Encomenda:** `pending → confirmed → preparing → ready → completed`. Cancelar é possível até `ready`.

**Entrega:** `pending → assigned → in_transit → delivered`. Se falhar: `failed → rescheduled → assigned`.

O estado da encomenda é actualizado **a partir** do pagamento e da entrega, não à mão:
- Pagamento confirmado (ou pagamento na entrega aceite) → encomenda `confirmed`.
- Entrega `delivered` ou levantamento feito → encomenda `completed`.
- Cada mudança grava uma linha em `order_status_histories` (`field` = `status`, `payment_status` ou `delivery_status`).

Use `canTransitionTo()` dos enums antes de qualquer mudança.

## 5. Regras de stock (decisão para fixar já)

| Momento | Movimento |
|---|---|
| Encomenda criada | `reserve` |
| Encomenda cancelada ou reserva expirada, antes de `ready` | `release` |
| Encomenda passa a `ready` | `sale` |
| Cancelada depois de `ready`, e a mercadoria voltou | `return` |
| Chegada de produção ou compra | `entry` |
| Contagem física diferente do sistema | `adjustment` (com motivo) |

Esta regra fica **num só sítio** (`StockService`). Se um dia quiseres dar baixa ao confirmar, mudas só aí.

## 6. Criar uma encomenda (tudo numa transação)

1. Validar variantes activas e quantidades.
2. `lockForUpdate()` nas variantes dos itens, para dois clientes não comprarem o último.
3. Confirmar `quantity_on_hand - quantity_reserved >= quantidade` (se `track_stock`).
4. Criar a encomenda e os itens com snapshot de nome, SKU e preço.
5. Gerar o número depois de ter o `id`: `GF-{ano}-{id com 6 dígitos}`. Evita corridas.
6. Registar os movimentos `reserve`.
7. Definir `reservation_expires_at` (se a encomenda ficar à espera de pagamento).
8. Criar o `payment` (e a `delivery`, se `delivery_method = delivery`).
9. Gravar a primeira linha de histórico.

Um job agendado (a cada poucos minutos) cancela encomendas por pagar com `reservation_expires_at` ultrapassado e faz `release`.

## 7. Webhooks de pagamento

1. Inserir em `payment_webhook_events` (`unique` em `provider` + `event_id`).
2. Se já existir, responder `200` e parar.
3. Processar, actualizar `payments` e a encomenda, e marcar `processed_at`.

## 8. Chaves iniciais em `settings`

`store_name`, `store_phone`, `store_whatsapp`, `store_email`, `store_address`, `currency` (MZN), `order_number_prefix` (GF), `reservation_minutes`, `pickup_enabled`, `pickup_address`, `cod_enabled`, `bank_transfer_details`, `payment_instructions`, `low_stock_default`, `store_open`.

## 9. Fica de fora da Fase 1 (de propósito)

Carrinho em tabelas (na Fase 1 o carrinho vive na sessão), cupões e promoções com datas, lotes e validade, avaliações, relatórios, produção e matérias-primas, fornecedores e compras.

## 10. Pacotes e passos em redor

- Tabelas da fila (`jobs`, `failed_jobs`) com o comando da tua versão do Laravel, e um worker em produção.
- `spatie/laravel-activitylog` para auditoria (quem mudou preços, stock e estados).
- Papéis da equipa (operador, stock, entregador): decide entre alargar o enum `UserRole` ou usar `spatie/laravel-permission`, antes de criar o módulo de Configurações.
- Biblioteca de imagem (ex.: Intervention Image) para miniaturas e WebP.
- Copiar `enums/` para `app/Enums/`.

## 11. Ordem de construção

1. Configurações e papéis
2. Categorias e produtos (com variantes)
3. Stock
4. Catálogo público
5. Carrinho e checkout
6. Encomendas
7. Pagamentos
8. Entregas
9. Dashboard