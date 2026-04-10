<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Groq\Controllers\ChatController;

Route::middleware('throttle:30,1')->post('/chat', ChatController::class);
