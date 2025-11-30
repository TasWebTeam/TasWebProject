<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RecetaService;

class GestionarRecetaController extends Controller
{
    private RecetaService $recetaService;
    public function __construct(RecetaService $recetaService)
    {
        $this->recetaService = $recetaService;
    }

    public function recetas()
    {
        $idSucursal = session('usuario.id_sucursal');
        $recetas = $this->recetaService->obtenerRecetasEmpleado($idSucursal);
        return view('empleado.recetas', compact('recetas'));
    }
    public function recetasExpiradas()
    {
        $idSucursal = session('usuario.id_sucursal');

        $recetas = $this->recetaService->obtenerRecetasExpiradas($idSucursal);

        return view('empleado.recetas_expiradas', compact('recetas'));
    }

    /*public function recetas()
    { 
        return view('empleado.recetas');
    }

    public function recetasExpiradas()
    {
        // Más adelante aquí traerás recetas expiradas
        return view('empleado.recetas_expiradas');
    }*/
    /**
     * Obtiene la sucursal del empleado desde la sesión.
     * OJO: debes guardar 'id_sucursal' del empleado en la sesión al iniciar sesión.
     */


    public function devolverReceta(int $idReceta){

    }

    public function confirmarRecetaNoRecogida(int $idReceta){

    }

}