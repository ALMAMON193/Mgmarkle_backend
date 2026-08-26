<?php

use App\Http\Middleware\RecordStack;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases here
        $middleware->alias([
            'stack.record'      => RecordStack::class,
            'advanced.throttle' => \App\Http\Middleware\AdvancedThrottle::class,
            'auth.rate.limit'   => \App\Http\Middleware\AuthRateLimit::class,
            'role'              => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
