<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmpleadoService;
use App\Services\SucursalService;
use Illuminate\Support\Facades\Log;


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

    public function recetasExpiradas()
    {
        $idSucursal = session('usuario.id_sucursal');
        $idCadena = session('usuario.id_cadena');
        $nombreSucursal = session('usuario.nombre_sucursal');
        $nombreCadena = session('usuario.nombre_cadena');    
        $recetas = $this->empleadoService->obtenerRecetasExpiradas($idCadena, $idSucursal);

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
            Log::info('Iniciando devolución de receta', ['idReceta' => $idReceta]);

            // Verificar que la receta existe antes de intentar devolverla
            $receta = \App\Models\RecetaModel::find($idReceta);
            if (!$receta) {
                Log::error('Receta no encontrada', ['idReceta' => $idReceta]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'La receta no existe.',
                    ], 404);
                }

                return redirect()->back()->with('error', 'La receta no existe.');
            }

            Log::info('Receta encontrada', [
                'idReceta' => $idReceta,
                'estado' => $receta->estado,
                'id_sucursal' => $receta->id_sucursal,
            ]);

            // Intentar devolver la receta en el servicio
            $okDevolucion = $this->sucursalService->devolverReceta($idReceta);

            Log::info('Resultado devolverReceta()', [
                'idReceta' => $idReceta,
                'okDevolucion' => $okDevolucion
            ]);

            if (!$okDevolucion) {
                Log::warning('No se pudo iniciar la devolución de la receta', [
                    'idReceta' => $idReceta,
                    'mensaje' => 'El servicio devolverReceta() retornó false. Revisar SucursalService.'
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'No se pudo iniciar la devolución de la receta. Verifica los logs del servidor.',
                    ], 422);
                }

                return redirect()
                    ->back()
                    ->with('error', 'No se pudo iniciar la devolución de la receta.');
            }

            // Actualizar el estado de la receta
            $okEstado = $this->empleadoService->actualizarEstado($idReceta, 'devolviendo');

            Log::info('Resultado actualizarEstado()', [
                'idReceta' => $idReceta,
                'okEstado' => $okEstado
            ]);

            $okFinal = $okDevolucion && $okEstado;

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => $okFinal,
                    'nuevoEstado' => $okFinal ? 'devolviendo' : null,
                    'message' => $okFinal
                        ? 'La devolución se inició correctamente. La receta está en estado "devolviendo".'
                        : 'No se pudo actualizar el estado de la receta.',
                ], $okFinal ? 200 : 422);
            }

            return redirect()
                ->back()
                ->with(
                    $okFinal ? 'success' : 'error',
                    $okFinal
                        ? 'La devolución se inició correctamente. La receta está en estado "devolviendo".'
                        : 'No se pudo actualizar el estado de la receta.'
                );

        } catch (\Throwable $e) {
            Log::error('Error al devolver la receta', [
                'idReceta' => $idReceta,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
            Log::error('Error al confirmar receta como no recogida', [
                'idReceta' => $idReceta,
                'error' => $e->getMessage(),
            ]);

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