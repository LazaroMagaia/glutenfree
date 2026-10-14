<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductCategory;
use App\Models\Admin\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ImageService $imageService,
    ) {}

    public function index(Request $request, $categorySlug)
    {
        $products = $this->productRepository->findProductsByCategory($categorySlug);

        if ($request->filled('search')) {
            $search = mb_strtolower($request->string('search')->toString());

            $products = $products->filter(fn ($product) =>
                str_contains(mb_strtolower($product->name), $search)
                || str_contains(mb_strtolower($product->slug), $search)
            );
        }

        return view('Backend.Admin.Products.index', compact('products', 'categorySlug'));
    }

    public function create($categorySlug)
    {
        $category = ProductCategory::with('parent')
            ->where('slug', $categorySlug)
            ->firstOrFail();

        return view('Backend.Admin.Products.create', compact('categorySlug', 'category'));
    }

    public function edit($productSlug,$categorySlug)
    {
        $product = $this->findProductInCategory($categorySlug, $productSlug);

        $category = ProductCategory::with('parent')->findOrFail($product->category_id);

        return view('Backend.Admin.Products.edit', compact('product', 'category', 'categorySlug'));
    }

    public function show($categorySlug, $productId)
    {
        $product = $this->findProductInCategory($categorySlug, $productId);

        return view('Backend.Admin.Products.show', compact('product', 'categorySlug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->productRules(), [], $this->attributes());

        $category = ProductCategory::where('slug', $data['category_slug'])->firstOrFail();

        $uploadedPaths = [];

        try {
            DB::transaction(function () use ($request, $data, $category, &$uploadedPaths) {
                $productData = $this->extractProductData($data);
                $productData['category_id'] = $category->id;
                $productData['slug'] = $this->uniqueSlug($productData['name']);

                $product = $this->productRepository->create($productData);

                // Imagem principal (obrigatória na criação).
                $this->upload($request->file('primary_image'), $product, true, 0, $uploadedPaths);

                // Imagens adicionais.
                foreach ($request->file('images', []) as $index => $image) {
                    $this->upload($image, $product, false, $index + 1, $uploadedPaths);
                }
            });
        } catch (Throwable $e) {
            $this->imageService->deleteMany($uploadedPaths);
            throw $e;
        }

        return redirect()
            ->route('admin.products.index', ['category' => $category->slug])
            ->with('success', 'Produto criado com sucesso.');
    }

    public function update(Request $request, $categorySlug, $productId)
    {
        $product = $this->findProductInCategory($categorySlug, $productId);

        $data = $request->validate($this->productRules(true), [], $this->attributes());

        $uploadedPaths = [];
        $oldPathsToDelete = [];

        try {
            DB::transaction(function () use ($request, $data, $product, &$uploadedPaths, &$oldPathsToDelete) {
                // O slug e a categoria não mudam na edição.
                $this->productRepository->update($product->id, $this->extractProductData($data));

                // Substitui a imagem principal, se uma nova foi enviada.
                if ($request->hasFile('primary_image')) {
                    $oldPrimary = $product->images()->where('is_primary', true)->first();

                    $this->upload($request->file('primary_image'), $product, true, 0, $uploadedPaths);

                    if ($oldPrimary) {
                        $oldPathsToDelete[] = $oldPrimary->path;
                        $oldPrimary->delete();
                    }
                }

                // Adiciona imagens à galeria sem apagar as existentes.
                $nextOrder = (int) $product->images()->max('sort_order') + 1;

                foreach ($request->file('images', []) as $index => $image) {
                    $this->upload($image, $product, false, $nextOrder + $index, $uploadedPaths);
                }
            });
        } catch (Throwable $e) {
            $this->imageService->deleteMany($uploadedPaths);
            throw $e;
        }

        // Só apaga os ficheiros antigos depois de a transação ter sucesso.
        $this->imageService->deleteMany($oldPathsToDelete);

        return redirect()
            ->route('admin.products.edit', [
                'categorySlug' => $categorySlug,
                'productId' => $product->id,
            ])
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroyImage($categorySlug, $productId, $imageId)
    {
        $product = $this->findProductInCategory($categorySlug, $productId);

        $image = $product->images()->findOrFail($imageId);

        if ($image->is_primary) {
            return back()->withErrors([
                'image' => 'Define outra imagem como principal antes de remover esta.',
            ]);
        }

        $path = $image->path;
        $image->delete();

        $this->imageService->delete($path);

        return back()->with('success', 'Imagem removida com sucesso.');
    }

    public function setPrimaryImage($categorySlug, $productId, $imageId)
    {
        $product = $this->findProductInCategory($categorySlug, $productId);

        $product->images()->findOrFail($imageId)->makePrimary();

        return back()->with('success', 'Imagem principal atualizada com sucesso.');
    }

    public function destroy($categorySlug, $productId)
    {
        $product = $this->findProductInCategory($categorySlug, $productId);

        // Guarda os caminhos antes de apagar os registos.
        $paths = $product->images()->pluck('path')->all();

        DB::transaction(function () use ($product) {
            $product->images()->delete();
            $this->productRepository->delete($product->id);
        });

        // Os ficheiros só são apagados depois de a transação ter sucesso.
        $this->imageService->deleteMany($paths);

        return redirect()
            ->route('admin.products.index', ['category' => $categorySlug])
            ->with('success', 'Produto eliminado definitivamente.');
    }
    /**
     * Procura o produto e garante que pertence à categoria do URL.
     */
    private function findProductInCategory($categorySlug, $productId): Product
    {
        $product = $this->productRepository->find((int) $productId);

        abort_unless($product->category?->slug === $categorySlug, 404);

        return $product;
    }

    private function upload(
        UploadedFile $image,
        Product $product,
        bool $isPrimary = false,
        int $sortOrder = 0,
        array &$uploadedPaths = [],
    ): ProductImage {
        $path = $this->imageService->upload($image, 'products');
        $uploadedPaths[] = $path;

        if ($isPrimary) {
            $product->images()->where('is_primary', true)->update(['is_primary' => false]);
        }

        return $product->images()->create([
            'path' => $path,
            'alt' => $product->name,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder,
        ]);
    }

    private function productRules(bool $updating = false): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_slug' => ['required', 'string', Rule::exists('product_categories', 'slug')],

            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'string'],
            'allergens' => ['nullable', 'string'],
            'cross_contamination_notice' => ['nullable', 'string'],
            'gluten_free_certification' => ['nullable', 'string', 'max:255'],

            'nutrition' => ['nullable', 'array'],
            'nutrition.*' => ['nullable', 'numeric', 'min:0'],

            'storage_info' => ['nullable', 'string'],
            'preparation_info' => ['nullable', 'string'],

            'status' => ['required', Rule::enum(ProductStatus::class)],

            'is_featured' => ['sometimes', 'boolean'],
            'is_new' => ['sometimes', 'boolean'],
            'is_favorite' => ['sometimes', 'boolean'],
            'is_purchasable' => ['sometimes', 'boolean'],

            'primary_image' => [
                $updating ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    private function attributes(): array
    {
        return [
            'name' => 'nome',
            'category_slug' => 'categoria',
            'short_description' => 'descrição curta',
            'description' => 'descrição completa',
            'ingredients' => 'ingredientes',
            'allergens' => 'alergénios',
            'cross_contamination_notice' => 'aviso de contaminação cruzada',
            'gluten_free_certification' => 'certificação sem glúten',
            'nutrition' => 'informação nutricional',
            'nutrition.*' => 'valor nutricional',
            'storage_info' => 'conservação',
            'preparation_info' => 'preparação',
            'status' => 'estado',
            'primary_image' => 'imagem principal',
            'images' => 'imagens adicionais',
            'images.*' => 'imagem adicional',
        ];
    }

    /**
     * Remove ficheiros e campos que não pertencem à tabela de produtos.
     */
    private function extractProductData(array $data): array
    {
        $data = collect($data)
            ->except(['primary_image', 'images', 'category_id', 'category_slug', 'slug'])
            ->all();

        foreach (['is_featured', 'is_new', 'is_favorite', 'is_purchasable'] as $field) {
            $data[$field] = (bool) ($data[$field] ?? false);
        }

        // Remove valores nutricionais vazios; sem nenhum valor, grava null.
        if (array_key_exists('nutrition', $data)) {
            $nutrition = array_filter(
                (array) $data['nutrition'],
                fn ($value) => $value !== null && $value !== ''
            );

            $data['nutrition'] = $nutrition ?: null;
        }

        return $data;
    }

    /**
     * Gera um slug único a partir do nome.
     */
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'produto';
        $slug = $base;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}