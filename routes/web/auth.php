<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

Route::inertia('login', 'Auth/Login')
    ->middleware('guest')
    ->name('login');

Route::post('login', static function (LoginRequest $request, AuthenticatedSessionController $controller): RedirectResponse {
    $controller->store($request);
    return redirect(route('home'));
})->middleware('guest')
    ->name('login');

Route::get('logout', static function (Request $request, AuthenticatedSessionController $controller): RedirectResponse {
    $controller->destroy($request);
    return redirect(route('auth.login'));
})->middleware('auth')
    ->name('logout');
