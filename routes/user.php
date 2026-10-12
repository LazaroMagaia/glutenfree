<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Admin\HomeController as UserHomeController;

/*
|--------------------------------------------------------------------------
| Rotas autenticadas: admin e user
|--------------------------------------------------------------------------
| O middleware "role" (EnsureRole) manda cada utilizador para a sua área.
*/

Route::middleware('auth')->group(function () {

    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserHomeController::class, 'index'])->name('index');    // user.index
    }); 
});
