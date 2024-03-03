<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index'])->name('index');
Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
