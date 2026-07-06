<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @param  \Throwable  $exception
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Always show error report page with report button
        try {
            // For API requests, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $exception->getMessage(),
                    'status' => 'error'
                ], $this->getExceptionStatus($exception));
            }

            // For authenticated users, show error report page
            if (auth()->check()) {
                return response()->view('errors.error_report', [
                    'exception' => $exception,
                    'message' => $exception->getMessage(),
                ], $this->getExceptionStatus($exception));
            }

            // For unauthenticated users, show basic error but still allow reporting
            return response()->view('errors.error_report', [
                'exception' => $exception,
                'message' => $exception->getMessage(),
            ], $this->getExceptionStatus($exception));
        } catch (\Exception $e) {
            // If error view fails, render default parent handler
            return parent::render($request, $exception);
        }
    }

    /**
     * Get HTTP status code from exception
     */
    private function getExceptionStatus($exception): int
    {
        if ($this->isHttpException($exception)) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Get the title for HTTP status code
     */
    private function getHttpStatusTitle(int $status): string
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            413 => 'Payload Too Large',
            414 => 'URI Too Long',
            415 => 'Unsupported Media Type',
            419 => 'Session Expired',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
        ];

        return $titles[$status] ?? 'Error';
    }
}
