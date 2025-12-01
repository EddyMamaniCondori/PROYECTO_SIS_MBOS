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
        // Registrar middlewares de Spatie Permission con rutas completas
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
        // 2. AGREGA ESTO: Excepción de CSRF para que JMeter pase sin token
        $middleware->validateCsrfTokens(except: [
            'estudiantes',      // La ruta exacta del POST
            'estudiantes/*',    // Por si acaso tienes sub-rutas
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
