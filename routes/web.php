<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function () {
    Route::inertia('/', 'Page1/Show')->name('page1');
    Route::inertia('page2', 'Page2/Show')->name('page2');
    Route::inertia('profile', 'Users/ProfileEdit')->name('me.profile');
    Route::inertia('settings', 'Users/UserSettings')->name('me.settings');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy']);
});
