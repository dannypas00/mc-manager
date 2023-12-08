<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

Route::as('auth.')->prefix('auth')->middleware(['web'])->group(static function () {
    Route::inertia('login', 'Auth/Login')
        ->middleware('guest')
        ->name('login');

    Route::post(
        'login',
        static function (LoginRequest $request, AuthenticatedSessionController $controller): RedirectResponse {
            $controller->store($request);
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    )->middleware('guest')
        ->name('authenticate');

    Route::get(
        'logout',
        static function (Request $request, AuthenticatedSessionController $controller): RedirectResponse {
            $controller->destroy($request);
            return redirect(route('auth.login'));
        }
    )->middleware('auth')
        ->name('logout');
});

Route::as('api.auth.')->prefix('api/auth')->middleware(['api'])->group(static function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest')
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest')
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('password.store');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.send');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});
