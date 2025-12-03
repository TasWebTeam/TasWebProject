<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloEmpleado
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario') || session('usuario.rol') !== 'empleado') {
            return redirect()->route('tas_inicioView')->with('error', 'No tienes acceso.');
        }
        return $next($request);
    }

}
