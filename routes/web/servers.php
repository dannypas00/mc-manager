<?php

declare(strict_types=1);

Route::prefix('{id}')->group(static function () {
    Route::inertia('', 'Servers/Console/ServerConsole')->name('show');
    Route::inertia('console', 'Servers/Console/ServerConsole')->name('console');
    Route::inertia('files', 'Servers/Files/ServerFiles')->name('files');
    Route::inertia('settings', 'Servers/Settings/ServerSettings')->name('settings');
})->whereNumber('id');
