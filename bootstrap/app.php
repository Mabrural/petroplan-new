<?php

use App\Http\Middleware\SetActivePeriod;
use App\Http\Middleware\CheckIfUserIsActive;
use App\Http\Middleware\CheckIfUserIsAdmin;
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
        $middleware->alias([
            'active' => CheckIfUserIsActive::class,
            'admin' => CheckIfUserIsAdmin::class,
            'period' => SetActivePeriod::class,
        ]);

        $middleware->group('web', [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            SetActivePeriod::class, // Tambahkan ini agar aktif di semua route web
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
