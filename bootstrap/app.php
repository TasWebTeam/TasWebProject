<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerificarSesionUsuario;
use App\Http\Middleware\RedirigirSiAutenticado;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         $middleware->alias([
            'verificar.sesion' => VerificarSesionUsuario::class,
            'redirigir.si.autenticado' => RedirigirSiAutenticado::class,
            'solo.empleado' => \App\Http\Middleware\SoloEmpleado::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();
