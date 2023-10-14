<?php

use App\Http\Controllers\Users\UserIndexController;
use App\Http\Controllers\Users\UserShowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->name('current')->get('/current', function (Request $request) {
    return $request->user();
});

Route::get('', UserIndexController::class)->name('index');
Route::prefix('{id}')->group(static function () {
    Route::get('', UserShowController::class)->name('show');
});
