<?php

declare(strict_types=1);

use App\Http\Controllers\Servers\ServerStoreController;
use App\Http\Controllers\Storage\StoragePathController;
use Inertia\Inertia;

Route::inertia('', 'Servers/ServerIndex')->name('index');
Route::inertia('create', 'Servers/ServerCreate')->name('create');
Route::post('create', ServerStoreController::class)->name('create');

Route::prefix('{id}')->group(static function () {
    Route::get('', static fn (int $id) => redirect(route('servers.console', ['id' => $id])))->name('show');
    Route::inertia('console', 'Servers/Console/ServerConsole')->name('console');
    Route::get('files/path/{path?}', StoragePathController::class)->name('files')->where('path', '\S+');
    Route::inertia('settings', 'Servers/Settings/ServerSettings')->name('settings');

    Route::get('edit', static fn (int $id) => Inertia::render(
        'Servers/ServerEdit',
        ['server' => Auth::user()?->servers()->findOrFail($id)]
    ))->name('edit');
})->whereNumber('id');
