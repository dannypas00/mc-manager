<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;

/**
 * @codeCoverageIgnore This is only used in local development
 */
class AutoLogin
{
    public function handle($request, Closure $next)
    {
        // If auto login is enabled and the app is not in production, skip authentication
        if (config()->boolean('auth.auto_login') && !app()->isProduction() && !Auth::user()) {
            if ($user = User::first()) {
                Auth::login($user, true);
            }
        }

        return $next($request);
    }
}
