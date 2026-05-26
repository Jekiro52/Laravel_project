<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'maintenance' => App\Http\Middleware\DownForMaintenanceMw::class,
            'promotion' => App\Http\Middleware\PromotionMw::class,
            'auth.user' => App\Http\Middleware\SessionUserAccount::class,
            'session.account' => App\Http\Middleware\SessionUserAccount::class,
            'password.changed' => App\Http\Middleware\EnsurePasswordHasBeenChanged::class,
            'role' => App\Http\Middleware\EnsureUserHasRole::class,
        ]);

        $middleware->group('groupMiddleware', [
            App\Http\Middleware\MiddlewareOne::class,
            App\Http\Middleware\MiddlewareTwo::class,
        ]);
    
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
