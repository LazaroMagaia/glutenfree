<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Failed = 'failed';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Por atribuir',
            self::Assigned => 'Atribuída',
            self::InTransit => 'Em trânsito',
            self::Delivered => 'Entregue',
            self::Failed => 'Falhada',
            self::Rescheduled => 'Reagendada',
            self::Cancelled => 'Cancelada',
        };
    }

    /** @return array<int, self> */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Assigned, self::Cancelled],
            self::Assigned => [self::InTransit, self::Cancelled],
            self::InTransit => [self::Delivered, self::Failed],
            self::Failed => [self::Rescheduled, self::Cancelled],
            self::Rescheduled => [self::Assigned, self::Cancelled],
            self::Delivered, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }
}
