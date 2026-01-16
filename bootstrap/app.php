<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Enables Sanctum's cookie-based authentication for React
        $middleware->statefulApi();

        // 2. Ensures every request (API & Web) sets the correct language (AR/EN)
        $middleware->append(\App\Http\Middleware\SetLocale::class);

        // 3. Registers your custom "role" middleware so you can use it in routes
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 4. Optional: If you face CSRF issues during development,
        // you can ensure React is trusted here.
        $middleware->validateCsrfTokens(except: [
            // 'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
