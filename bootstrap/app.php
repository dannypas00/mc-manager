<?php

declare(strict_types=1);

use App\Http\Middleware\AutoLogin;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: base_path('routes/web.php'),
        api: base_path('routes/api.php'),
        commands: base_path('routes/console.php'),
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->web(append: [
                AutoLogin::class,
                HandleInertiaRequests::class,
                AddLinkHeadersForPreloadedAssets::class,
            ])
            ->statefulApi()
            ->trustProxies('*')
            ->validateCsrfTokens();
    })
    ->withBroadcasting(base_path('routes/channels.php'), attributes: ['middleware' => ['api', 'auth:sanctum']])
    ->withExceptions()
    ->create();
