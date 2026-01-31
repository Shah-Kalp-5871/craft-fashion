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
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'admin.api.auth' => \App\Http\Middleware\AdminApiAuth::class,
            'customer.auth' => \App\Http\Middleware\CustomerAuth::class,
            'customer.api.auth' => \App\Http\Middleware\CustomerApiAuth::class,
            'sync.cart' => \App\Http\Middleware\SyncCartAfterLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();