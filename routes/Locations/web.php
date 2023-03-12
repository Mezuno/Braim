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


Route::prefix('/locations')->group(function() {
    Route::post('/', [App\Http\Controllers\LocationController::class, 'store'])->name('location.store');
    Route::get('/{pointId}', [App\Http\Controllers\LocationController::class, 'view'])->where('pointId', '[0-9-]+')->name('location.view');
    Route::put('/{pointId}', [App\Http\Controllers\LocationController::class, 'update'])->where('pointId', '[0-9-]+')->name('location.update');
    Route::delete('/{pointId}', [App\Http\Controllers\LocationController::class, 'delete'])->where('pointId', '[0-9-]+')->name('location.delete');
});
