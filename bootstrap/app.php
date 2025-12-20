<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))

    // 1. ADD THE ->registered() CHAIN TO CHANGE THE PUBLIC PATH
    ->registered(function ($app) {
        $app->usePublicPath(path: realpath(base_path('public_html')));
    })

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
// 1. EXCLUDE THE TEST ROUTE FROM CSRF
        $middleware->validateCsrfTokens(except: [
            'abc/*',
        ]);

        // 2. ADD SPATIE MIDDLEWARE ALIASES HERE
        $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

            // ADD THIS BLOCK
            $exceptions->stopIgnoring(Illuminate\Auth\Access\AuthorizationException::class);
            $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e) {
                // DUMP THE STACK TRACE TO SEE WHERE THE ABORT(403) CAME FROM
                dd($e->getTraceAsString());
            });
    })->create();
