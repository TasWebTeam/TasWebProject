<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GestionarRecetaController extends Controller
{
    public function recetas()
    { 
        return view('empleado.recetas');
    }

    public function recetasExpiradas()
    {
        // Más adelante aquí traerás recetas expiradas
        return view('empleado.recetas_expiradas');
    }
    
    public function devolverReceta(int $idReceta){

    }

    public function confirmarRecetaNoRecogida(int $idReceta){

    }

}