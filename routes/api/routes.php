<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::group([], function (): void {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('products')->as('products.')->group(function (): void {
    require __DIR__ . '/v1/products/routes.php';
});

Route::prefix('categories')->as('categories.')->group(function (): void {
    require __DIR__ . '/v1/categories/routes.php';
});
