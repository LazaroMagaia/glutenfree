<?php

namespace App\Enums;

/**
 * orders.payment_status usa todos os valores.
 * payments.status usa só: Pending, Paid, Failed, Cancelled (reembolsos ficam na tabela refunds).
 */
enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case PartiallyRefunded = 'partially_refunded';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Por pagar',
            self::Pending => 'Pagamento pendente',
            self::Paid => 'Pago',
            self::Failed => 'Falhado',
            self::Cancelled => 'Cancelado',
            self::PartiallyRefunded => 'Parcialmente reembolsado',
            self::Refunded => 'Reembolsado',
        };
    }
}
