<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::as('api.users.')->prefix('api/users')->middleware('auth:sanctum')->group(static function () {
    Route::get('/me', static function (Request $request) {
        return $request->user();
    })->name('current')->middleware('auth:sanctum');
});
