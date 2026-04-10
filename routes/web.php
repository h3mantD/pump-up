<?php

declare(strict_types=1);

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

// Public storefront
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Docs
Route::get('/docs', fn () => Inertia\Inertia::render('Docs'))->name('docs');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Root redirect: guests go to products, authenticated users go to dashboard
Route::get('/', fn (): Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => auth()->check() ? redirect('/dashboard') : redirect('/products'));

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
