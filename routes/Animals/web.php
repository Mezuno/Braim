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
    Route::get('/{animalId}', [App\Http\Controllers\AnimalController::class, 'view'])->where('animalId', '[0-9]+')->name('animal.view');
    Route::post('/', [App\Http\Controllers\AnimalController::class, 'store'])->name('animal.store');
    Route::put('/{animalId}', [App\Http\Controllers\AnimalController::class, 'update'])->where('animalId', '[0-9]+')->name('animal.update');
    Route::delete('/{animalId}', [App\Http\Controllers\AnimalController::class, 'delete'])->where('animalId', '[0-9]+')->name('animal.delete');
    Route::get('/search', [App\Http\Controllers\AnimalController::class, 'search'])->name('animal.search');
    Route::get('/{animalId}/locations', [App\Http\Controllers\AnimalController::class, 'locations'])->where('animalId', '[0-9]+')->name('animal.locations');

    Route::prefix('/types')->group(function() {
       Route::get('/{typeId}', [App\Http\Controllers\AnimalController::class, 'viewType'])->where('typeId', '[0-9]+')->name('animal.type.view');
       Route::post('/', [App\Http\Controllers\AnimalController::class, 'storeType'])->name('animal.type.store');
       Route::put('/{typeId}', [App\Http\Controllers\AnimalController::class, 'updateType'])->where('typeId', '[0-9]+')->name('animal.type.update');
       Route::delete('/{typeId}', [App\Http\Controllers\AnimalController::class, 'deleteType'])->where('typeId', '[0-9]+')->name('animal.type.delete');
    });
});

