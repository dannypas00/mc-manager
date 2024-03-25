<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function () {
    Route::inertia('/', 'Page1/Show')->name('page1');
    Route::inertia('page2', 'Page2/Show')->name('page2');
});
