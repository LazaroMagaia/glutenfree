<?php

namespace App\Repositories\Contracts;

use App\Models\Admin\Product;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function findProductsByCategory(string $categorySlug): Collection;

    public function findProductsBySubCategory(int $subCategoryId): Collection;

    public function find(int $id): Product;

    public function create(array $data): Product;

    public function update(int $id, array $data): Product;

    public function delete(int $id): bool;
}