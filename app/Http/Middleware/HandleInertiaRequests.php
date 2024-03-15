<?php

namespace App\Http\Middleware;

use App\DataObjects\UserData;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user'             => fn () => $request->user() && UserData::from(...$request->user()),
            'route_parameters' => fn () => $request->route()->parameters,
        ]);
    }
}
