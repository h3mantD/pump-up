<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Groq\Controllers\ChatController;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/chat', ChatController::class);
});
