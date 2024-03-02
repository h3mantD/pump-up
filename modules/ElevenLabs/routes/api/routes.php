<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1/eleven-labs')
    ->group(base_path('modules/ElevenLabs/routes/api/v1/routes.php'));
