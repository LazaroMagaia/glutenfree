<?php

namespace App\Enums;

enum StockMovementType: string
{
    // Manuais (admin)
    case Entry = 'entry';              // entrada de stock
    case Adjustment = 'adjustment';    // contagem física (define o novo total)
    case Loss = 'loss';                // perda / roubo / validade
    case Damage = 'damage';            // fica em stock, mas indisponível
    case Discard = 'discard';          // descarta stock danificado

    // Automáticos (encomendas, para mais tarde)
    case Reservation = 'reservation';  // cliente fez encomenda
    case Release = 'release';          // encomenda cancelada
    case Sale = 'sale';                // encomenda entregue

    public function label(): string
    {
        return match ($this) {
            self::Entry => 'Entrada',
            self::Adjustment => 'Ajuste (contagem)',
            self::Loss => 'Perda',
            self::Damage => 'Dano',
            self::Discard => 'Descarte de danificados',
            self::Reservation => 'Reserva',
            self::Release => 'Libertação de reserva',
            self::Sale => 'Venda',
        };
    }

    public function requiresReason(): bool
    {
        return in_array($this, [self::Adjustment, self::Loss, self::Damage, self::Discard], true);
    }

    /** @return array<int, self> */
    public static function manual(): array
    {
        return [self::Entry, self::Adjustment, self::Loss, self::Damage, self::Discard];
    }
}