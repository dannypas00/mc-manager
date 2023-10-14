<?php

namespace App\Providers;

use DannyPas00\LaravelDynamicRoutes\RouteServiceProvider as ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Str;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        parent::boot();
    }

    /**
     * @param string $directory
     * @return string|null
     */
    protected function matchMiddleware(string $directory): string|null
    {
        if (Str::startsWith($directory, 'api')) {
            return 'api';
        }

        if (Str::startsWith($directory, 'web')) {
            return 'web';
        }

        return null;
    }
}
