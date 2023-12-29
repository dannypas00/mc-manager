<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore we trust laravel default providers work
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!$this->app->environment('production')) {
            $this->app->register(FakerServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
