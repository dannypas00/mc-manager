<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;

class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        // If autho login is enabled and the app is not in production, skip authentication
        if (config()->boolean('auth.auto_login') && !app()->isProduction() && !Auth::check()) {
            if ($user = User::first()) {
                Auth::login($user, true);
            }
        }

        return parent::handle($request, $next, ...$guards);
    }
}
