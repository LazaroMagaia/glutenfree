<?php

namespace App\Models\Admin;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Admin\StockMovement;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'ingredients',
        'allergens',
        'cross_contamination_notice',
        'gluten_free_certification',
        'nutrition',
        'storage_info',
        'preparation_info',
        'status',
        'is_featured',
        'is_new',
        'is_favorite',
        'is_purchasable',
        'sku',
        'price',
        'sale_price',
        'weight_grams',
        'track_stock',
        'quantity_on_hand',
        'low_stock_threshold',
        'quantity_reserved',
        'quantity_unavailable',
    ];
    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'nutrition' => 'array',
            'status' => ProductStatus::class,
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_favorite' => 'boolean',
            'is_purchasable' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Obtém a imagem principal.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getAvailableAttribute(): int
    {
        return max(0, $this->quantity_on_hand - $this->quantity_reserved - $this->quantity_unavailable);
    }

    /** 'untracked' | 'out' | 'low' | 'ok': para o badge da lista */
    public function getStockStatusAttribute(): string
    {
        if (! $this->track_stock) return 'untracked';
        if ($this->available <= 0) return 'out';
        if ($this->available <= $this->low_stock_threshold) return 'low';
        return 'ok';
    }
}