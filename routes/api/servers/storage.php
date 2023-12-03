<?php

use App\Http\Controllers\Storage\StorageContentController;
use App\Http\Controllers\Storage\StorageListingController;

Route::prefix('{id}')->group(static function () {
    Route::get('listing', StorageListingController::class)->name('listing');
    Route::get('content', StorageContentController::class)->name('content');
})->whereNumber('id');
