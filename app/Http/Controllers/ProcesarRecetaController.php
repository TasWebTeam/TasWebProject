<?php

namespace App\Http\Controllers;

use App\Services\RecetaService;
use App\Services\TasService;
use Illuminate\Http\Request;

class ProcesarRecetaController extends Controller
{
    private RecetaService $recetaService;
    private TasService $tasService;

    public function __construct(TasService $tasService, RecetaService $recetaService)
    {
        $this->tasService = $tasService;
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
        $usuario = session('usuario');

        $tarjeta = $this->tasService->obtenerTarjetaUsuario($usuario['id']);

        $numTarjeta = $tarjeta->getLast4();

        $resultado = $this->recetaService->procesarReceta($numTarjeta);

        $usuarioCorreo = session('usuario.correo');
        $receta = $this->recetaService->obtenerPacienteReceta($usuarioCorreo);
        $fechaRecoleccion = $receta->getFechaRecoleccion();

        if ($resultado == true) {

            return view('tas.resultado', [
                'exito' => true,
                'numeroPedido' => $receta->getIdReceta(),
                'cedulaProfesional' => $request->cedula_profesional,
                'farmacia' => $request->farmacia_cadena . ' - Sucursal ' . $request->farmacia_sucursal,
                'medicamentos' => $medicamentos,
                'fechaRecoleccion' => $fechaRecoleccion->format('Y-m-d H:i:s'), 
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