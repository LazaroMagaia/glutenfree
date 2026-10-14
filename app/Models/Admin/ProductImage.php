<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'alt',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'path' => 'string',
            'alt' => 'string',
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Define esta imagem como a principal do produto.
     */
    public function makePrimary(): void
    {
        DB::transaction(function () {
            $product = Product::query()
                ->lockForUpdate()
                ->findOrFail($this->product_id);

            $product->images()
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            $this->is_primary = true;
            $this->save();
        });
    }

    /**
     * Verifica se a imagem pertence ao produto indicado.
     */
    public function belongsToProduct(Product $product): bool
    {
        return (int) $this->product_id === (int) $product->id;
    }
}