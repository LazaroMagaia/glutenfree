<?php

namespace App\Repositories\Eloquent;

use App\Models\Admin\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Relações carregadas para apresentar os produtos.
     */
    private function relations(): array
    {
        return [
            'category.parent',
            'images',
            'primaryImage',
        ];
    }

    public function all(): Collection
    {
        return Product::with($this->relations())
            ->latest()
            ->get();
    }

    public function findProductsByCategory(string $categorySlug): Collection
    {
        return Product::with($this->relations())
            ->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            })
            ->latest()
            ->get();
    }

    public function findProductsBySubCategory(int $subCategoryId): Collection
    {
        return Product::with($this->relations())
            ->where('category_id', $subCategoryId)
            ->latest()
            ->get();
    }

    public function find(int $id): Product
    {
        return Product::with($this->relations())
            ->findOrFail($id);
    }

    public function create(array $data): Product
    {
        $product = Product::create($data);

        return $product->load($this->relations());
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->find($id);

        $product->update($data);

        return $product->load($this->relations());
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);

        // Mantém o comportamento de soft delete do modelo Product.
        return (bool) $product->delete();
    }
}