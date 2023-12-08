<?php

use App\Http\Controllers\Storage\StorageContentController;
use App\Http\Controllers\Storage\StorageListingController;
use App\Http\Controllers\Storage\StorageWriteController;

Route::prefix('{id}')->group(static function () {
    Route::get('listing', StorageListingController::class)->name('listing');
    Route::get('content', StorageContentController::class)->name('content');
    Route::post('{path}', StorageWriteController::class)->name('write');
})->whereNumber('id');
