<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Anti-Redirect Loop: Jika user sudah login mencoba buka halaman Login (Guest)
        $middleware->redirectUsersTo(function () {
            if (Auth::user()->role === 'admin') {
                return route('admin.dashboard');
            }
            return route('user.dashboard');
        });

        // Jika orang luar mencoba masuk ke halaman admin/user tanpa login
        $middleware->redirectGuestsTo(fn () => route('login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();