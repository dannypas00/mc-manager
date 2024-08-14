<?php

use App\Actions\Fortify\CreateNewUser;
use App\DataObjects\UserData;
use App\Http\Controllers\Servers\ServerStoreController;
use App\Http\Controllers\Storage\StoragePathController;
use App\Http\Controllers\Users\UserQueryBuilderController;
use App\Http\Controllers\Users\UserUpdateController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function () {
    Route::inertia('/', 'Home')->name('home');

    Route::name('api')->as('web.api.')->group(static function () {
        Route::as('users.')->prefix('users')->group(static function () {
            Route::get('me', static fn (Request $request) => $request->user())->name('current');
            Route::get('', [UserQueryBuilderController::class, 'index'])->name('index');
            Route::get('{id}', [UserQueryBuilderController::class, 'show'])->name('show');
            Route::get('all', [UserQueryBuilderController::class, 'all'])->name('all');
            Route::post(
                'create',
                static fn () => UserData::from((new CreateNewUser())->create(request()?->input()))
            )->name('create');
            Route::put('{user}', UserUpdateController::class)->name('update');
        });
    });

    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('auth.logout');

    Route::prefix('servers')->as('servers.')->group(static function () {
        Route::inertia('', 'Servers/ServerIndex')->name('index');
        Route::inertia('create', 'Servers/ServerCreate')->name('create');
        Route::post('create', ServerStoreController::class)->name('create');

        Route::prefix('{id}')->group(static function () {
            Route::get('', static fn (int $id) => redirect(route('servers.console', ['id' => $id])))->name('show');
            Route::inertia('console', 'Servers/Console/ServerConsole')->name('console');
            Route::get('files/path{path?}', StoragePathController::class)->name('files')->where('path', '\S+');
            Route::inertia('settings', 'Servers/Settings/ServerSettings')->name('settings');

            Route::get('edit', static fn (int $id) => Inertia::render(
                'Servers/ServerEdit',
                ['server' => Auth::user()?->servers()->findOrFail($id)]
            ))->name('edit');
        })->whereNumber('id');
    });
});
