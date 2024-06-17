<?php

use App\Http\Controllers\Servers\ServerEulaAcceptedController;
use App\Http\Controllers\Servers\ServerIndexController;
use App\Http\Controllers\Servers\ServerLogStreamController;
use App\Http\Controllers\Servers\ServerRconCommandController;
use App\Http\Controllers\Servers\ServerShowController;
use App\Http\Controllers\Servers\ServerStoreController;
use Illuminate\Support\Facades\Route;

Route::get('', ServerIndexController::class)->name('index');

Route::prefix('{id}')->group(static function () {
    Route::get('', ServerShowController::class)->name('show');
    Route::get('eula-accepted', ServerEulaAcceptedController::class)->name('eula-accepted');
    Route::get('logs', ServerLogStreamController::class)->name('logs');
    Route::post('command', ServerRconCommandController::class)->name('command');
})->whereNumber('id');
