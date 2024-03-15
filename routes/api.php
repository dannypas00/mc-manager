<?php

use App\Http\Controllers\Users\UserQueryBuilderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::as('api.')->prefix('api/v1')->middleware('auth:sanctum')->group(static function () {
    Route::as('users.')->prefix('users')->group(static function () {
        Route::get('me', static fn (Request $request) => $request->user())->name('current');
        Route::get('', [UserQueryBuilderController::class, 'index'])->name('index');
        Route::get('{id}', [UserQueryBuilderController::class, 'show'])->name('show');
        Route::get('all', [UserQueryBuilderController::class, 'all'])->name('all');
    });
});
