<?php

namespace App\Providers;

use DannyPas00\LaravelDynamicRoutes\RouteServiceProvider as ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Str;

/**
 * @codeCoverageIgnore we trust laravel default providers work
 */
class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        parent::boot();
    }

    protected function matchMiddleware(string $directory): ?string
    {
        if (Str::startsWith($directory, 'api')) {
            return 'authapi';
        }

        if (Str::startsWith($directory, 'web')) {
            return 'authweb';
        }

        return null;
    }
}
