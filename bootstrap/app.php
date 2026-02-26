<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\AuthenticatedUser::class,
            'role' => \App\Http\Middleware\CheckUserRole::class,
            'branch.context' => \App\Http\Middleware\BranchContext::class,
            'permission' => \App\Http\Middleware\Permission::class,
            'access' => \App\Http\Middleware\CheckAccess::class, // Combined middleware for role and permission checks


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
