<?php

namespace App\Exceptions;

use App\Signing\Shared\Exception\DomainException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
            return redirect(url('/404'));
        }
        if ($exception instanceof DomainException) {
            return response()->json([
                'data' => [
                    'message' => $exception->getMessage(),
                ]
            ], 430);
        }
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->view('error.403', [], 403);
        }
        return Parent::render($request, $exception);
    }

    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
