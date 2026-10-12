<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\{HomeController,UserController};

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
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    }); 
});
