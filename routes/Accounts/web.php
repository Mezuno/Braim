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
    Route::get('/{accountId}', [App\Http\Controllers\AccountController::class, 'view'])->name('accounts.view');
    Route::get('/search', [App\Http\Controllers\AccountController::class, 'view'])->name('accounts.search');
});
