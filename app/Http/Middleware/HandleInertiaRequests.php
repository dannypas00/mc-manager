<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * @codeCoverageIgnore We trust laravel default middleware does its job
 */
class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user'        => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'icon')
                : null,
            'route_parameters' => fn () => $request->route()->parameters,
        ]);
    }
}
