<?php

use App\Http\Controllers\Servers\ServerEulaAcceptedController;
use App\Http\Controllers\Servers\ServerIndexController;
use App\Http\Controllers\Servers\ServerLogStreamController;
use App\Http\Controllers\Servers\ServerRconCommandController;
use App\Http\Controllers\Servers\ServerShowController;
use App\Http\Controllers\Storage\StorageContentController;
use App\Http\Controllers\Storage\StorageDeleteController;
use App\Http\Controllers\Storage\StorageListingController;
use App\Http\Controllers\Storage\StorageWriteController;
use App\Http\Controllers\Users\UserIndexController;
use App\Http\Controllers\Users\UserShowController;

Route::as('api.')->group(static function () {
    Route::prefix('users')->as('users.')->group(static function () {
        Route::middleware(['auth:sanctum'])->name('current')->get('/current', function (Request $request) {
            return $request->user();
        });

        Route::get('', UserIndexController::class)->name('index');
        Route::prefix('{id}')->whereNumber('id')->group(static function () {
            Route::get('', UserShowController::class)->name('show');
        });
    });

    Route::prefix('servers')->as('servers.')->group(static function () {
        Route::get('', ServerIndexController::class)->name('index');

        Route::prefix('{id}')->group(static function () {
            Route::get('', ServerShowController::class)->name('show');
            Route::get('eula-accepted', ServerEulaAcceptedController::class)->name('eula-accepted');
            Route::get('logs', ServerLogStreamController::class)->name('logs');
            Route::post('command', ServerRconCommandController::class)->name('command');
        })->whereNumber('id');

        Route::prefix('storage')->as('storage.')->group(static function () {
            Route::prefix('{id}')->group(static function () {
                Route::get('listing', StorageListingController::class)->name('listing');
                Route::get('content', StorageContentController::class)->name('content');
                Route::post('{path}', StorageWriteController::class)->name('write');
                Route::delete('', StorageDeleteController::class)->name('delete');
            })->whereNumber('id');
        });
    });
});
