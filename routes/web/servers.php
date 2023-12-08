<?php

declare(strict_types=1);

use App\Http\Controllers\Storage\StoragePathController;

Route::prefix('{id}')->group(static function () {
    Route::get('', static fn (int $id) => redirect(route('servers.console', ['id' => $id])))->name('show');
    Route::inertia('console', 'Servers/Console/ServerConsole')->name('console');
    Route::get('files/path/{path?}', StoragePathController::class)->name('files');
    Route::inertia('settings', 'Servers/Settings/ServerSettings')->name('settings');
})->whereNumber('id');
