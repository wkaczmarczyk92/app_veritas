<?php

namespace App\Exceptions;

use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if (auth()->check() && $exception instanceof UnauthorizedException) {
            $user = auth()->user();

            if ($user->hasAnyRole(['admin', 'super-admin', 'god_mode'])) {
                return redirect('/pulpit');
            }

            if ($user->hasRole('user')) {
                return redirect('/');
            }
        }

        return parent::render($request, $exception);
    }
}
