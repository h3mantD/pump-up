<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ElevenLabs\Controllers\TextToSpeechController;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/text-to-speech', TextToSpeechController::class);
});
