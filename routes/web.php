<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function () {
    Route::inertia('/', 'Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION
    ]);

    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
});
