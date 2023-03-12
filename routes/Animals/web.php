<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;

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

    Route::post('/', [AnimalController::class, 'store'])->name('animal.store');
    Route::get('/{animalId}', [AnimalController::class, 'view'])->where('animalId', '[0-9-]+')->name('animal.view');
    Route::put('/{animalId}', [AnimalController::class, 'update'])->where('animalId', '[0-9-]+')->name('animal.update');
    Route::delete('/{animalId}', [AnimalController::class, 'delete'])->where('animalId', '[0-9-]+')->name('animal.delete');

    Route::get('/search', [AnimalController::class, 'search'])->name('animal.search');
    Route::get('/{animalId}/locations', [AnimalController::class, 'locations'])->where('animalId', '[0-9-]+')->name('animal.locations');

    Route::post('/{animalId}/types/{typeId}', [AnimalController::class, 'addType'])->where('animalId', '[0-9-]+')->where('typeId', '[0-9-]+')->name('animal.type.add');
    Route::put('/{animalId}/types/', [AnimalController::class, 'changeType'])->where('animalId', '[0-9-]+')->name('animal.type.change');
    Route::delete('/{animalId}/types/{typeId}', [AnimalController::class, 'removeType'])->where('animalId', '[0-9-]+')->where('typeId', '[0-9-]+')->name('animal.type.remove');

    Route::post('/{animalId}/locations/{pointId}', [AnimalController::class, 'addLocation'])->where('animalId', '[0-9-]+')->where('pointId', '[0-9-]+')->name('animal.location.add');
    Route::put('/{animalId}/locations/', [AnimalController::class, 'changeLocation'])->where('animalId', '[0-9-]+')->name('animal.location.change');
    Route::delete('/{animalId}/locations/{visitedPointId}', [AnimalController::class, 'removeLocation'])->where('animalId', '[0-9-]+')->where('visitedPointId', '[0-9-]+')->name('animal.location.remove');

    Route::prefix('/types')->group(function() {
        Route::post('/', [AnimalController::class, 'storeType'])->name('animal.type.store');
        Route::get('/{typeId}', [AnimalController::class, 'viewType'])->where('typeId', '[0-9-]+')->name('animal.type.view');
        Route::put('/{typeId}', [AnimalController::class, 'updateType'])->where('typeId', '[0-9-]+')->name('animal.type.update');
        Route::delete('/{typeId}', [AnimalController::class, 'deleteType'])->where('typeId', '[0-9-]+')->name('animal.type.delete');
    });
});

