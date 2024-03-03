<?php

declare(strict_types=1);

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'products',
    'as' => 'products.',
], function (): void {
    Route::get('/', [ProductsController::class, 'index'])->name('index');
    Route::get('/{product}/reviews', [ProductsController::class, 'getReviews'])->name('reviews');
    Route::get('/{product}', [ProductsController::class, 'show'])->name('show');
});
