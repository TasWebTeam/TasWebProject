<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{


    public function recetas()
    {
        // Más adelante traerás las recetas desde la BD
        return view('empleado.recetas');
    }

    public function recetasExpiradas()
    {
        // Más adelante aquí traerás recetas expiradas
        return view('empleado.recetas_expiradas');
    }
}
