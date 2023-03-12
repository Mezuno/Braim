<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::prefix('/accounts')->group(function() {
    Route::get('/search', [App\Http\Controllers\AccountController::class, 'search'])->name('accounts.search');

    Route::get('/{accountId}', [App\Http\Controllers\AccountController::class, 'view'])->where('accountId', '[0-9-]+')->name('accounts.view');
    Route::put('/{accountId}', [App\Http\Controllers\AccountController::class, 'update'])->where('accountId', '[0-9-]+')->name('accounts.update');
    Route::delete('/{accountId}', [App\Http\Controllers\AccountController::class, 'delete'])->where('accountId', '[0-9-]+')->name('accounts.delete');
});
