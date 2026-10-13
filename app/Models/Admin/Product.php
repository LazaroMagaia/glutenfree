<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
#[Fillable(['category_id', 'name', 'slug', 'short_description', 'description', 'ingredients', 'allergens', 'cross_contamination_notice', 
    'gluten_free_certification', 'nutrition', 'storage_info', 'preparation_info', 'status', 'is_featured', 'is_new',
     'is_favorite', 'is_purchasable'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'nutrition' => 'array',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_favorite' => 'boolean',
            'is_purchasable' => 'boolean',
        ];
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}