<?php

use App\Http\Controllers\Servers\ServerTestFtpController;
use App\Http\Controllers\Servers\ServerTestSshCommandController;
use App\Http\Controllers\Servers\ServerUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('{id}')->group(static function () {
    Route::put('', ServerUpdateController::class)->name('update');
})->whereNumber('id');

Route::prefix('test')->as('test.')->group(static function () {
    Route::post('ssh', ServerTestSshCommandController::class)->name('ssh');
    Route::post('ftp', ServerTestFtpController::class)->name('ftp');
});
