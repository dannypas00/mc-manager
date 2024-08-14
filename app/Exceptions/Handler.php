<?php

namespace App\Exceptions;

use Auth;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @codeCoverageIgnore Turns out it is nigh-impossible to test this class, so we trust that a single if-check works as expected
 */
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        // If the user is not logged in, redirect them to the login screen (or return 401 if json) on exception
        // This is done to prevent enumerating pages as an unauthenticated actor
        if (!Auth::check() && !app()->hasDebugModeEnabled()) {
            return $request->expectsJson()
                ? new JsonResponse(null, Response::HTTP_UNAUTHORIZED)
                : redirect(route('login'));
        }

        return parent::render($request, $e);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
