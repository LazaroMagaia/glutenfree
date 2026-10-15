<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CashOnDelivery = 'cash_on_delivery';
    case BankTransfer = 'bank_transfer';
    case MobileMoney = 'mobile_money';   // o operador/gateway vai em payments.provider
    case Card = 'card';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'Pagamento na entrega',
            self::BankTransfer => 'Transferência bancária',
            self::MobileMoney => 'Pagamento móvel',
            self::Card => 'Cartão',
            self::Other => 'Outro',
        };
    }
}
