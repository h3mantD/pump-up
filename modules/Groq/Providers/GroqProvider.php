<?php

declare(strict_types=1);

namespace Modules\Groq\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Modules\Groq\Services\Groq;

final class GroqProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // code...
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'gorq');

        $this->app->singleton(
            abstract: Groq::class,
            concrete: function (): Groq {
                $apiToken = config('gorq.api_token');

                return new Groq(pendingRequest: Http::baseUrl(
                    url: 'https://api.groq.com/openai/v1/'
                )
                    ->asJson()
                    ->acceptJson()
                    ->withToken(is_string($apiToken) ? $apiToken : ''));
            },
        );

        $this->app->register(RouteServiceProvider::class);
    }
}
