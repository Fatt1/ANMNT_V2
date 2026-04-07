<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                return null;
            }

            // Do not override Laravel's core redirect exceptions
            if ($e instanceof \Illuminate\Auth\AuthenticationException || 
                $e instanceof \Illuminate\Validation\ValidationException ||
                $e instanceof \Illuminate\Session\TokenMismatchException) {
                return null;
            }

            $status = match (true) {
                $e instanceof HttpExceptionInterface => $e->getStatusCode(),
                $e instanceof ModelNotFoundException => 404,
                default => 500,
            };

            // Show only explicit HTTP exception messages (e.g. abort(400, '...')).
            // Do not expose internal exception details for non-HTTP errors.
            $message = null;
            if ($e instanceof HttpExceptionInterface) {
                $message = trim($e->getMessage()) !== '' ? $e->getMessage() : null;
            }

            return response()->view('errors.generic', [
                'status' => $status,
                'message' => $message,
            ], $status);
        });
    })->create();
