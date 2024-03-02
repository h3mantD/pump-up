<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('v1/groq')
    ->group(base_path('modules/Groq/routes/api/v1/routes.php'));
