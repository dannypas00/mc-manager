<?php

declare(strict_types=1);

use App\Repositories\Servers\FrontendServerShowRepository;

Route::prefix('{id}')->group(static function () {
    Route::get('', static fn (int $id) => redirect(route('servers.console', ['id' => $id])))->name('show');
    Route::inertia('console', 'Servers/Console/ServerConsole')->name('console');
    Route::inertia('files/path/{path?}', 'Servers/Files/ServerFiles')->name('files');
    Route::inertia('settings', 'Servers/Settings/ServerSettings')->name('settings');
})->whereNumber('id');
