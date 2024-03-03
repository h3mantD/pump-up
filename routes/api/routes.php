<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('products')->as('products.')->group(function (): void {
    require __DIR__ . '/v1/products/routes.php';
});

Route::prefix('categories')->as('categories.')->group(function (): void {
    require __DIR__ . '/v1/categories/routes.php';
});
