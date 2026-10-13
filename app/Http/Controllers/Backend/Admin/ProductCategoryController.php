<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Product\{CategoryRequest,CategoryUpdateRequest};
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ImageService;
class ProductCategoryController extends Controller
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository, 
        private ImageService $imageService
        )
    {
    }
    public function index()
    {
        $categories = $this->categoryRepository->all();
        return view('Backend.Admin.Products.Category.index', compact('categories'));
    }
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->upload(
                $request->file('image'),
                'categories'
            );
        }

        try {
            $this->categoryRepository->create($data);
        } catch (\Throwable $e) {
            if (!empty($data['image'])) {
                $this->imageService->delete($data['image']);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.products.categories.index')
            ->with('success', 'Categoria criada com sucesso.');
    }
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        return view('Backend.Admin.Products.Category.edit', compact('category'));
    }
   
    public function update(CategoryUpdateRequest $request, string $slug)
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            abort(404);
        }

        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $oldImage = $category->image;
        $newImage = null;

        // Guardar a nova imagem, se tiver sido enviada.
        if ($request->hasFile('image')) {
            $newImage = $this->imageService->upload(
                $request->file('image'),
                'categories'
            );

            $data['image'] = $newImage;
        }

        try {
            // Atualizar utilizando o slug atual da categoria.
            $updatedCategory = $this->categoryRepository->update(
                $category->slug,
                $data
            );

            if (!$updatedCategory) {
                abort(404);
            }
        } catch (\Throwable $e) {
            // Remover a imagem nova se a atualização falhar.
            if ($newImage) {
                $this->imageService->delete($newImage);
            }

            throw $e;
        }

        // Apagar a imagem anterior apenas se a nova foi guardada.
        if ($newImage && $oldImage) {
            $this->imageService->delete($oldImage);
        }

        return redirect()
            ->route('admin.products.categories.index')
            ->with('success', 'Categoria atualizada com sucesso.');
    }
    public function destroy(string $slug)
    {
        $category = $this->categoryRepository->findBySlug($slug);

        if (!$category) {
            abort(404);
        }

        // Guardar o caminho da imagem antes de apagar a categoria.
        $imagePath = $category->image;

        // Apagar a categoria.
        $deleted = $this->categoryRepository->delete($slug);

        if ($deleted && $imagePath) {
            // Apagar a imagem apenas se a categoria foi apagada com sucesso.
            $this->imageService->delete($imagePath);
        }

        return redirect()
            ->route('admin.products.categories.index')
            ->with('success', 'Categoria eliminada com sucesso.');
    }
}
