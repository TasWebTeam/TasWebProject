<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmpleadoService;
use Illuminate\Support\Facades\Log;

class GestionarRecetaController extends Controller
{
    private EmpleadoService $empleadoService;
    public function __construct(EmpleadoService $empleadoService)
    {
        $this->empleadoService = $empleadoService;
    }

    public function recetas(Request $request)
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session(key: 'usuario.id_cadena');
        $estado = $request->query(key: 'estado');
        $nombreSucursal = session('usuario.nombre_sucursal');
        $recetas = $this->empleadoService->obtenerRecetasEmpleado($idCadena,$idSucursal, $estado); 
        return view('empleado.recetas', compact('recetas', 'estado', 'nombreSucursal'));
    }

    public function recetasExpiradas()
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session('usuario.id_cadena');
        
        $recetas = $this->empleadoService->obtenerRecetasExpiradas($idCadena,$idSucursal);

        return view('empleado.recetas_expiradas', compact('recetas'));
    }

    // 🔹 Marcar como lista_para_recoleccion
    public function marcarComoLista(Request $request, int $id)
    {
        $ok = $this->empleadoService->marcarComoLista($id);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => $ok,
                'nuevoEstado' => $ok ? 'lista_para_recoleccion' : null,
            ], $ok ? 200 : 422);
        }

        return redirect()->back()->with(
            $ok ? 'success' : 'error',
            $ok ? 'Receta marcada como lista para recolección.' : 'No se pudo actualizar la receta.'
        );
    }

    
    // 🔹 Marcar como entregada
    public function marcarComoEntregada(Request $request, int $id)
    {
        $ok = $this->empleadoService->marcarComoEntregada($id);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => $ok,
                'nuevoEstado' => $ok ? 'entregada' : null,
            ], $ok ? 200 : 422);
        }

        return redirect()->back()->with(
            $ok ? 'success' : 'error',
            $ok ? 'Receta marcada como entregada.' : 'No se pudo actualizar la receta.'
        );
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


    /*public function devolverReceta(int $idReceta){
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session(key: 'usuario.id_cadena');
        // 🔹 obtenemos la sucursal como objeto de dominio
        $sucursal = $this->recetaService->obtenerSucursalPorCadenaYSucursal($idCadena, $idSucursal);
        $sucursal->devolverReceta($idReceta);
    }*/
    public function devolverReceta(Request $request, int $idReceta)
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena   = session('usuario.id_cadena');

        if (!$idSucursal || !$idCadena) {
            return response()->json([
                'ok'      => false,
                'message' => 'No se encontró sucursal/cadena en la sesión.'
            ], 400);
        }

        try {
            // 🔹 Obtenemos la sucursal de dominio (ya tienes este método)
            $sucursal = $this->empleadoService
                             ->obtenerSucursalPorCadenaYSucursal($idCadena, $idSucursal);

            if (!$sucursal) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'No se encontró la sucursal.'
                ], 404);
            }

            // 🔹 Llamas a tu dominio, que ya hace toda la magia de devolución
            $sucursal->devolverReceta($idReceta); //falta definir REPOS 😏

            return response()->json([
                'ok'      => true,
                'message' => 'La receta se marcó como devolviendo y se actualizaron inventarios.'
            ]);

        } catch (\Throwable $e) {
             Log::error($e->getMessage());
            return response()->json([
                'ok'      => false,
                'message' => 'Ocurrió un error al devolver la receta.'
            ], 500);
        }
    }

    public function confirmarRecetaNoRecogida(Request $request,int $idReceta)
    {
        try {
            // Solo cambiamos el estado a "no_recogida"
            $ok = $this->empleadoService->actualizarEstado($idReceta, 'no_recogida');

            if (!$ok) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'No se encontró la receta o no se pudo actualizar.'
                ], 400);
            }

            return response()->json([
                'ok'      => true,
                'message' => 'La receta se marcó como NO RECOGIDA y ya no aparecerá en esta lista.'
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al confirmar receta como no recogida', [
                'idReceta' => $idReceta,
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'ok'      => false,
                'message' => 'Ocurrió un error al confirmar la receta como no recogida.'
            ], 500);
        }
    }

}