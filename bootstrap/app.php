<?php

use App\Http\Middleware\CheckSessionTimeout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckSessionTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->reportable(function (Throwable $e) {
            try {
                activity('system')
                    ->event('system.error')
                    ->withProperties([
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'code' => $e->getCode(),
                        'class' => get_class($e),
                        'url' => request()->fullUrl(),
                        'method' => request()->method(),
                        'ip' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log("Erreur système ({$e->getMessage()}) dans {$e->getFile()}:{$e->getLine()}");
            } catch (Throwable $ignore) {
                // Safeguard against nested exception logging issues
            }
        });
    })->create();
