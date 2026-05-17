<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApprovedMiddleware;
use App\Http\Middleware\CheckSuspendedMiddleware;
use App\Http\Middleware\ConsumerMiddleware;
use App\Http\Middleware\FarmerMiddleware;
use App\Http\Middleware\SetLocaleMiddleware;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'farmer' => FarmerMiddleware::class,
            'consumer' => ConsumerMiddleware::class,
            'approved' => ApprovedMiddleware::class,
        ]);

        $middleware->web(append: [
            SetLocaleMiddleware::class,
            CheckSuspendedMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
