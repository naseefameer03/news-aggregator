<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Contracts\NewsSourceInterface::class, function ($app, $params) {
            // Optionally, you can resolve a specific service by $params['service']
            return $app->make($params['service'] ?? \App\Services\NewsAPIService::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
