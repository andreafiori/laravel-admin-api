<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
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
     * Report or log an exception.
     *
     * @param \Throwable $exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Unauthenticated method
        if ( get_class($exception) == 'Illuminate\Auth\AuthenticationException') {
            return $this->unauthenticated($request, $exception);
        }

        // Testing model not found
        if ($exception instanceof ModelNotFoundException) {
            return response(['error' => 'unauthenticated'], 404);
        }
        // Debug
        // var_dump($exception->getMessage().' File: '.$exception->getFile().' Line: '.$exception->getLine());
        return response([
            // 'error' => $exception->getMessage().' File: '.$exception->getFile().' Line: '.$exception->getLine(),
            'error' => $exception->getMessage(),
        ], $exception->getCode() ? $exception->getCode() : 400);
    }

    /**
     * Unauthenticated handler
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function unauthenticated($request, AuthenticationException $exception)
    {
        return response(['error' => 'unauthenticated'], 401);
    }
}
