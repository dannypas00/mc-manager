<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    /**
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'icon')
                : null,
        ]);
    }
}
