<?php

use App\Actions\Fortify\CreateNewUser;
use App\DataObjects\UserData;
use App\Http\Controllers\Users\UserQueryBuilderController;
use App\Http\Controllers\Users\UserUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('me', static fn (Request $request) => $request->user())->name('current');
Route::get('', [UserQueryBuilderController::class, 'index'])->name('index');
Route::get('{id}', [UserQueryBuilderController::class, 'show'])->name('show');
Route::get('all', [UserQueryBuilderController::class, 'all'])->name('all');
Route::post(
    'create',
    static fn () => UserData::from((new CreateNewUser)->create(request()?->input()))
)->name('create');
Route::put('{user}', UserUpdateController::class)->name('update');
