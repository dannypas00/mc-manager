<?php

namespace App\Http\Middleware;

use App\Repositories\Users\AuthenticatedUserRepository;
use Auth;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * @codeCoverageIgnore We trust laravel default middleware does its job
 */
class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user'        => fn () => app(AuthenticatedUserRepository::class)->getAuthenticatedUser(),
            'route_parameters' => fn () => $request->route()->parameters,
        ]);
    }
}
