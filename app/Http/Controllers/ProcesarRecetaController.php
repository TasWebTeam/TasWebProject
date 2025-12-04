<?php

namespace App\Http\Controllers;

use App\Services\RecetaService;
use App\Services\SucursalService;
use Illuminate\Http\Request;

class ProcesarRecetaController extends Controller
{
    private SucursalService $sucursalService;
    private RecetaService $recetaService;

    public function __construct(SucursalService $sucursalService, RecetaService $recetaService)
    {
        $this->sucursalService = $sucursalService;
        $this->recetaService = $recetaService;
    }

    public function crearNuevaReceta(Request $request)
    {
        $usuarioCorreo = session('usuario.correo');
        $this->recetaService->crearNuevaReceta($usuarioCorreo);

        $cadena = $request->farmacia_cadena;
        $sucursal = $request->farmacia_sucursal;

        $this->introducirSucursal($cadena, $sucursal);
    }

    public function introducirSucursal(string $nombreCadena, string $nombreSucursal)
    {
        $this->recetaService->introducirSucursal($nombreCadena, $nombreSucursal);
    }

    public function introducirCedulaProfesional(string $cedulaProfesional)
    {
        $this->recetaService->introducirCedulaProfesional($cedulaProfesional);
    }

    public function introducirMedicamento($nombreMedicamento, $cantidad)
    {
        $this->recetaService->introducirMedicamento($nombreMedicamento, $cantidad);
    }

    public function procesarReceta(Request $request)
    {
        $this->crearNuevaReceta($request);

        $this->introducirCedulaProfesional($request->cedula_profesional);

        $medicamentos = json_decode($request->medicamentos, true);

        foreach ($medicamentos as $med) {
            $this->introducirMedicamento($med['nombre'], $med['cantidad']);
        }

        $numTarjeta = '1234 1234 1234 1234';

        $resultado = $this->recetaService->procesarReceta($numTarjeta);

        $fechaRecoleccion = now()->addDay()->setTime(10, 0)->format('d/m/Y H:i');

        if ($resultado == true) {

            return view('tas.resultado', [
                'exito' => true,
                
                'numeroPedido' => 1,
                'cedulaProfesional' => $request->cedula_profesional,
                'farmacia' => $request->farmacia_cadena . ' - Sucursal ' . $request->farmacia_sucursal,
                'medicamentos' => $medicamentos,
                'fechaRecoleccion' => $fechaRecoleccion,
                'mensaje' => 'Receta procesada correctamente',
            ]);
        }

        return view('tas.resultado', [
            'exito' => false,
            'mensaje' => 'No se pudo surtir todos los medicamentos',

            'cedulaProfesional' => $request->cedula_profesional,
            'farmacia' => $request->farmacia_cadena . ' - Sucursal ' . $request->farmacia_sucursal,
            'medicamentos' => $medicamentos,
        ]);
    }
}