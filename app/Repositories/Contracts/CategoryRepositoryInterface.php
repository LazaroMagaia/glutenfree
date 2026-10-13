<?php

namespace App\Repositories\Contracts;

use App\Models\Admin\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Retorna todas as categorias de produtos.
     */
    public function all(): Collection;

    /**
     * Procura uma categoria pelo slug.
     */
    public function findBySlug(string $slug): ?ProductCategory;

    /**
     * Cria uma nova categoria.
     */
    public function create(array $data): ProductCategory;

    /**
     * Atualiza uma categoria pelo slug.
     */
    public function update(string $slug, array $data): ?ProductCategory;

    /**
     * Elimina uma categoria pelo slug.
     */
    public function delete(string $slug): bool;
}