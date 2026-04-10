<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ElevenLabs\Controllers\TextToSpeechController;

Route::middleware('throttle:10,1')->post('/text-to-speech', TextToSpeechController::class);
