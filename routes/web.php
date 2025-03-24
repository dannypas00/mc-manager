<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function (): void {
    Route::name('api')->as('web.api.')->group(base_path('routes/web/webapi.php'));
    Route::name('pages')->group(base_path('routes/web/pages.php'));

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy']);
});
