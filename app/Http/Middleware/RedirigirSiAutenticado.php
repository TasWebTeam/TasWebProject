<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirigirSiAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('usuario')) {
            return redirect()->route('tas_inicioView');
        }
        return $next($request);
    }
}
