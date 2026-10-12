<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\HomeController as AdminHomeController;
/*
|--------------------------------------------------------------------------
| Rotas autenticadas: admin e user
|--------------------------------------------------------------------------
| O middleware "role" (EnsureRole) manda cada utilizador para a sua área.
*/

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('index');   // admin.index
    }); 
});
