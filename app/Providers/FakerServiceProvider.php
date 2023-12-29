<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore we trust laravel default providers work
 */
class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
