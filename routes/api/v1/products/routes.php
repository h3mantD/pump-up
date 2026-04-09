<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductsController::class, 'index'])->name('index');
Route::get('/{product}/reviews', [ProductsController::class, 'getReviews'])->name('reviews');
Route::get('/{product}', [ProductsController::class, 'show'])->name('show');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/', [ProductsController::class, 'store'])->name('store');
    Route::put('/{product}', [ProductsController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('destroy');
});
