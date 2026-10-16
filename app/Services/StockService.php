<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Admin\Product;
use App\Models\Admin\StockMovement;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function register(
        int $productId,
        StockMovementType $type,
        int $quantity,
        ?string $reason = null,
        ?int $userId = null,
        ?Model $reference = null,
        ?string $idempotencyKey = null,
    ): StockMovement {
        if ($idempotencyKey && ($existing = StockMovement::where('idempotency_key', $idempotencyKey)->first())) {
            return $existing;
        }

        try {
            return DB::transaction(function () use ($productId, $type, $quantity, $reason, $userId, $reference, $idempotencyKey) {
                $product = Product::query()->whereKey($productId)->lockForUpdate()->firstOrFail();

                if (! $product->track_stock) {
                    throw new DomainException('Este produto não tem o controlo de stock activo.');
                }

                if ($type->requiresReason() && blank($reason)) {
                    throw new DomainException('O motivo é obrigatório para este tipo de movimento.');
                }

                [$dOnHand, $dReserved, $dUnavailable] = $this->deltas($type, $quantity, $product);

                if ($dOnHand === 0 && $dReserved === 0 && $dUnavailable === 0) {
                    throw new DomainException('Este movimento não altera o stock.');
                }

                $onHand = $product->quantity_on_hand + $dOnHand;
                $reserved = $product->quantity_reserved + $dReserved;
                $unavailable = $product->quantity_unavailable + $dUnavailable;

                if ($onHand < 0 || $reserved < 0 || $unavailable < 0) {
                    throw new DomainException('Quantidade superior ao stock existente.');
                }

                if ($reserved + $unavailable > $onHand) {
                    throw new DomainException(
                        'Stock insuficiente: o stock físico não pode ficar abaixo do reservado + indisponível.'
                    );
                }

                $product->forceFill([
                    'quantity_on_hand' => $onHand,
                    'quantity_reserved' => $reserved,
                    'quantity_unavailable' => $unavailable,
                ])->save();

                return StockMovement::create([
                    'product_id' => $product->id,
                    'type' => $type,
                    'on_hand_delta' => $dOnHand,
                    'reserved_delta' => $dReserved,
                    'unavailable_delta' => $dUnavailable,
                    'on_hand_after' => $onHand,
                    'reserved_after' => $reserved,
                    'unavailable_after' => $unavailable,
                    'reason' => $reason,
                    'reference_type' => $reference?->getMorphClass(),
                    'reference_id' => $reference?->getKey(),
                    'idempotency_key' => $idempotencyKey,
                    'user_id' => $userId,
                ]);
            });
        } catch (UniqueConstraintViolationException $e) {
            // duplo clique / retry simultâneo com a mesma chave
            if ($idempotencyKey) {
                return StockMovement::where('idempotency_key', $idempotencyKey)->firstOrFail();
            }

            throw $e;
        }
    }

    /** @return array{0:int,1:int,2:int} [on_hand, reserved, unavailable] */
    private function deltas(StockMovementType $type, int $q, Product $product): array
    {
        return match ($type) {
            StockMovementType::Entry => [$q, 0, 0],
            StockMovementType::Adjustment => [$q - $product->quantity_on_hand, 0, 0],
            StockMovementType::Loss => [-$q, 0, 0],
            StockMovementType::Damage => [0, 0, $q],
            StockMovementType::Discard => [-$q, 0, -$q],
            StockMovementType::Reservation => [0, $q, 0],
            StockMovementType::Release => [0, -$q, 0],
            StockMovementType::Sale => [-$q, -$q, 0],
        };
    }
}