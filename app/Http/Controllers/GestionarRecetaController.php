<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmpleadoService;
use App\Services\SucursalService;

class GestionarRecetaController extends Controller
{
    private EmpleadoService $empleadoService;
    private SucursalService $sucursalService;

    public function __construct(EmpleadoService $empleadoService, SucursalService $sucursalService)
    {
        $this->empleadoService = $empleadoService;
        $this->sucursalService = $sucursalService;
    }

    public function recetas(Request $request)
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session(key: 'usuario.id_cadena');
        $estado = $request->query(key: 'estado');
        $nombreSucursal = session('usuario.nombre_sucursal');
        $nombreCadena = session('usuario.nombre_cadena');
        $recetas = $this->empleadoService->obtenerRecetasEmpleado($idCadena, $idSucursal, $estado); 
        return view('empleado.recetas', compact('recetas', 'estado', 'nombreSucursal', 'nombreCadena'));
    }

    public function recetasExpiradas(Request $request)
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session('usuario.id_cadena');
        $estado = $request->query(key: 'estado');
        $nombreSucursal = session('usuario.nombre_sucursal');
        $nombreCadena = session('usuario.nombre_cadena');    
        $recetas = $this->empleadoService->obtenerRecetasExpiradas($idCadena, $idSucursal, $estado);

        return view('empleado.recetas_expiradas', compact('recetas', 'nombreSucursal', 'nombreCadena'));
    }

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


    public function devolverReceta(Request $request, int $idReceta)
    {
        try {
            if (!$this->sucursalService->devolverReceta($idReceta)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'No se pudo iniciar la devolución de la receta',
                    ], 422);
                }

                return redirect()
                    ->back()
                    ->with('error', 'No se pudo iniciar la devolución de la receta.');
            }

            if (!$this->empleadoService->actualizarEstado($idReceta, 'devolviendo')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'No se pudo actualizar el estado de la receta.',
                        'nuevoEstado' => null,
                    ], 422);
                }

                return redirect()
                    ->back()
                    ->with('error', 'No se pudo actualizar el estado de la receta.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'nuevoEstado' => 'devolviendo',
                    'message' => 'La devolución se inició correctamente. La receta está en estado "devolviendo".',
                ], 200);
            }

            return redirect()
                ->back()
                ->with('success', 'La devolución se inició correctamente. La receta está en estado "devolviendo".');

        } catch (\Throwable $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Ocurrió un error al devolver la receta: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al devolver la receta: ' . $e->getMessage());
        }
    }


    public function confirmarRecetaNoRecogida(Request $request, int $idReceta)
    {
        try {
            $ok = $this->empleadoService->actualizarEstado($idReceta, 'no_recogida');

            if (!$ok) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No se encontró la receta o no se pudo actualizar.'
                ], 400);
            }

            return response()->json([
                'ok' => true,
                'message' => 'La receta se marcó como NO RECOGIDA y ya no aparecerá en esta lista.'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Ocurrió un error al confirmar la receta como no recogida.'
            ], 500);
        }
    }

    public function mostrarMapa(int $idReceta)
    {
        $info = $this->empleadoService->obtenerRecetaYDetallesResumen($idReceta);

        $segmentos = $this->empleadoService->construirSegmentosMapaReceta($idReceta);
        
        return view('empleado.detalles_receta', [
            'receta' => $info['receta'],
            'detallesResumen' => $info['detallesResumen'],
            'totalGeneral' => $info['totalGeneral'],
            'comision' => $info['comision'],
            'totalConComision' => $info['totalConComision'],
            'segmentos' => $segmentos,
        ]);
    }
}