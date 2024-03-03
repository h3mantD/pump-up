<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->as('v1.')->group(function (): void {
    require __DIR__ . '/v1/products/routes.php';
});
