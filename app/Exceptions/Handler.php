<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\{Exceptions\HttpResponseException, JsonResponse, Response};
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException,
    BadRequestHttpException,
    MethodNotAllowedHttpException,
    NotFoundHttpException};
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

    public function render($request, Throwable $exception): JsonResponse|Response
    {
        $code = method_exists($exception, 'getCode') ? $exception->getCode() : '-1';

        return match (true) {
            $exception instanceof AccessDeniedHttpException,
            $exception instanceof NotFoundHttpException,
            $exception instanceof MethodNotAllowedHttpException,
            $exception instanceof BadRequestHttpException,
            $exception instanceof HttpResponseException => response()->json(
                [
                    'code'    => $code,
                    'type'    => 'error',
                    'message' => $exception->getMessage() ?: trans('exception.method_not_allowed'),
                ],
                $exception->getStatusCode()
            ),
            default => parent::render($request, $exception),
        };
    }
}
