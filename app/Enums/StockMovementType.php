<?php

namespace App\Enums;

/**
 * Efeito de cada tipo nos três contadores de product_variants
 * (on_hand = em stock, reserved = reservado, unavailable = indisponível):
 *
 *  Entry       on_hand +n
 *  Exit        on_hand -n
 *  Adjustment  on_hand ±n            (motivo obrigatório)
 *  Reserve     reserved +n
 *  Release     reserved -n
 *  Sale        on_hand -n, reserved -n
 *  Loss        on_hand -n            (motivo obrigatório)
 *  Damage      on_hand -n, unavailable +n (motivo obrigatório)
 *  Return      on_hand +n
 *
 * Disponível para venda = on_hand - reserved (calculado, nunca guardado).
 */
enum StockMovementType: string
{
    case Entry = 'entry';
    case Exit = 'exit';
    case Adjustment = 'adjustment';
    case Reserve = 'reserve';
    case Release = 'release';
    case Sale = 'sale';
    case Loss = 'loss';
    case Damage = 'damage';
    case Return = 'return';

    public function label(): string
    {
        return match ($this) {
            self::Entry => 'Entrada',
            self::Exit => 'Saída',
            self::Adjustment => 'Ajuste',
            self::Reserve => 'Reserva',
            self::Release => 'Libertação de reserva',
            self::Sale => 'Venda',
            self::Loss => 'Perda',
            self::Damage => 'Dano',
            self::Return => 'Devolução',
        };
    }

    public function requiresReason(): bool
    {
        return in_array($this, [self::Adjustment, self::Loss, self::Damage], true);
    }
}
