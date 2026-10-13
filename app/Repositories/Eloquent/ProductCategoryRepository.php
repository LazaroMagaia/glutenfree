<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin\ProductCategory;
class ProductCategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return ProductCategory::orderBy('name')->get();
    }

    public function findBySlug(string $slug): ?ProductCategory
    {
        $category = ProductCategory::where('slug', $slug)->first();
        return $category ? $category : null;
    }

    public function create(array $data): ProductCategory
    {
        $category = ProductCategory::create($data);
        return $category;
    }

    public function update(string $slug, array $data): ?ProductCategory
    {
        $category = ProductCategory::where('slug', $slug)->first();
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete(string $slug): bool
    {
        $category = ProductCategory::where('slug', $slug)->first();
        if ($category) {
            return (bool) $category->delete();
        }
        return false;
    }
}