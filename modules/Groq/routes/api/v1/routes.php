<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Groq\Controllers\ChatController;

Route::post('/chat', ChatController::class);
