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
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'redirect.admin.petani' => \App\Http\Middleware\RedirectIfAdminOrPetani::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            // Route yang memerlukan login buyer (cart, checkout, track)
            $buyerPaths = ['cart', 'checkout', 'track'];
            $currentPath = $request->segment(1);
            
            if (in_array($currentPath, $buyerPaths)) {
                return route('buyer.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
