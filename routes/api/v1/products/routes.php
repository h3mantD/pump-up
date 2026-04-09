<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductsController::class, 'index'])->name('index');
Route::get('/search', [ProductsController::class, 'search'])->name('search');
Route::get('/{product}/reviews', [ProductsController::class, 'getReviews'])->name('reviews');
Route::get('/{product}', [ProductsController::class, 'show'])->name('show');

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/bulk-delete', [ProductsController::class, 'bulkDelete'])->name('bulk-delete');
    Route::patch('/bulk-stock', [ProductsController::class, 'bulkUpdateStock'])->name('bulk-stock');
    Route::patch('/bulk-status', [ProductsController::class, 'bulkUpdateStatus'])->name('bulk-status');
    Route::post('/', [ProductsController::class, 'store'])->name('store');
    Route::put('/{product}', [ProductsController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductsController::class, 'destroy'])->name('destroy');
});
