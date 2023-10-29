<?php

use App\Http\Controllers\Servers\ServerIndexController;
use App\Http\Controllers\Servers\ServerShowController;
use App\Http\Controllers\Storage\StorageListingController;
use Illuminate\Support\Facades\Route;

Route::get('', ServerIndexController::class)->name('index');
Route::prefix('{id}')->group(static function () {
    Route::get('', ServerShowController::class)->name('show');
});

Route::prefix('{id}/storage')->as('storage.')->group(static function () {
    Route::get('listing', StorageListingController::class)->name('listing');
});
