<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StockMovementType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'on_hand_delta',
        'reserved_delta',
        'unavailable_delta',
        'on_hand_after',
        'reserved_after',
        'unavailable_after',
        'reason',
        'reference_type',
        'reference_id',
        'idempotency_key',
        'user_id',
    ];
    protected $casts = [
        'type' => StockMovementType::class,
        'on_hand_delta' => 'integer',
        'reserved_delta' => 'integer',
        'unavailable_delta' => 'integer',
        'on_hand_after' => 'integer',
        'reserved_after' => 'integer',
        'unavailable_after' => 'integer',
    ];
    protected static function booted(): void
    {
        static::updating(fn () => throw new LogicException('Movimentos de stock são imutáveis.'));
        static::deleting(fn () => throw new LogicException('Movimentos de stock não podem ser eliminados.'));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
