<?php

declare(strict_types=1);

namespace Modules\ElevenLabs\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Modules\ElevenLabs\Services\ElevenLabs;

final class ElevenLabsProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config.php',
            key: 'elevenlabs'
        );

        $this->app->singleton(
            abstract: ElevenLabs::class,
            concrete: fn (): ElevenLabs => new ElevenLabs(pendingRequest: Http::baseUrl(
                url: 'https://api.elevenlabs.io/v1/'
            )
                ->asJson()
                ->acceptJson()
                ->withHeader(name: 'xi-api-key', value: config('elevenlabs.api_token')))
        );

        $this->app->register(
            provider: RouteServiceProvider::class
        );
    }
}
