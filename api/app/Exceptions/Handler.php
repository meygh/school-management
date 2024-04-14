<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    protected function renderExceptionResponse($request, Throwable $e)
    {
        if ($e instanceof AccessDeniedHttpException) {
            if ($this->shouldReturnJson($request, $e)) {
                return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            }
        }

        return $this->shouldReturnJson($request, $e)
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['message' => 'آدرس مورد نظر شما یافت نشد'], 404);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json(['message' => 'آیتم مورد نظر شما یافت نشد'], 404);
        }

        return parent::render($request, $e);
    }

}
