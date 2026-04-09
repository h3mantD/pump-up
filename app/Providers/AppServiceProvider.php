<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;
use Modules\ElevenLabs\Providers\RouteServiceProvider as ElevenLabsRouteServiceProvider;
use Modules\Groq\Providers\RouteServiceProvider as GroqRouteServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->register(ElevenLabsRouteServiceProvider::class);
        $this->app->register(GroqRouteServiceProvider::class);
    }

    public function boot(): void
    {
        Product::observe(ProductObserver::class);
    }
}
