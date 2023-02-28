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


Route::prefix('/animals')->group(function() {
    Route::get('/{animalId}', [App\Http\Controllers\AnimalController::class, 'view'])->name('animal.view');
    Route::get('/search', [App\Http\Controllers\AnimalController::class, 'view'])->name('animal.search');
    Route::get('/types/{typeId}', [App\Http\Controllers\AnimalController::class, 'view'])->name('animal.type');
    Route::get('/{animalId}/locations', [App\Http\Controllers\AnimalController::class, 'view'])->name('animal.locations');
});
