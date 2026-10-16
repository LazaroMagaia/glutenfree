<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\{HomeController,UserController,SettingController,ProductCategoryController,ProductController,
        StockMovimentController};
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Rotas autenticadas: admin e user
|--------------------------------------------------------------------------
| O middleware "role" (EnsureRole) manda cada utilizador para a sua área.
*/

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');

        /**
         * Rotas para gestão de utilizadores (admin)
         */
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        /**
         * Rotas para gestão de configurações (admin)
         */
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        /*
        * Rotas para gestão de categorias de produtos (admin)
        */
        Route::get('products/categories', [ProductCategoryController::class, 'index'])->name('products.categories.index');
        Route::get('products/categories/create', [ProductCategoryController::class, 'create'])->name('products.categories.create');
        Route::post('products/categories', [ProductCategoryController::class, 'store'])->name('products.categories.store');
        Route::get('products/categories/{slug}/edit', [ProductCategoryController::class, 'edit'])->name('products.categories.edit');
        Route::put('products/categories/{slug}', [ProductCategoryController::class, 'update'])->name('products.categories.update');
        Route::delete('products/categories/{slug}', [ProductCategoryController::class, 'destroy'])->name('products.categories.destroy');
        /**
         * Rotas para gestão de produtos (admin)
         */

        Route::get('products/{category}/create', [ProductController::class, 'create'])
            ->name('products.create');
        Route::get('products/{category}', [ProductController::class, 'index'])
            ->name('products.index');
        Route::get('products/show/{category}/{slug}', [ProductController::class, 'show'])
            ->name('products.show');

        Route::post('products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('products/{id}/{category}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('products/{slug}/update/{id}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('products/{id}/category/{slug}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
        /**
         * Rotas para gestão de stock (admin)
         */
        Route::get('products/stock/{category}/{slug}', [StockMovimentController::class, 'index'])
            ->name('products.stock');
        Route::post('products/stock/{category}/{slug}', [StockMovimentController::class, 'store'])
            ->name('products.stock.store');

    }); 
});
