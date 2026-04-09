<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ElevenLabs\Providers\ElevenLabsProvider;
use Modules\Groq\Providers\GroqProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(GroqProvider::class);
        $this->app->register(ElevenLabsProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
