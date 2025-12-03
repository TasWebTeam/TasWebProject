<?php

namespace App\Http\Controllers;

use App\Domain\Cadena;
use App\Domain\DetalleReceta;
use App\Domain\Medicamento;
use App\Domain\Receta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Paciente;
use App\Domain\Pago;
use App\Domain\Sucursal;
use App\Models\CadenaModel;
use App\Services\SucursalService;
use App\Services\RecetaService;
use App\Services\TasService;
use DateTime;


class ProcesarRecetaController extends Controller
{
    private SucursalService $sucursalService; 
    private RecetaService $recetaService;

    public function __construct(SucursalService $sucursalService, RecetaService $recetaService)
    {
        $this->sucursalService = $sucursalService;
        $this->recetaService = $recetaService;
    }
    
    public function crearNuevaReceta(){             
        $usuarioCorreo= session('usuario.correo');      
        $this->recetaService->crearNuevaReceta($usuarioCorreo);

        $cadena = "Farmacias del Ahorro";
        $sucursal = "Cedros";

        $this->introducirSucursal($cadena, $sucursal);  
    }

    public function introducirSucursal(string $nombreCadena, string $nombreSucursal){   
        $this->recetaService->introducirSucursal($nombreCadena, $nombreSucursal);
    }

    public function introducirCedulaProfesional(string $cedulaProfesional){    
        $this->recetaService->introducirCedulaProfesional($cedulaProfesional);
    }
    
    public function introducirMedicamento($nombreMedicamento, $cantidad){
        $this->recetaService->introducirMedicamento($nombreMedicamento, $cantidad);
    }

    public function procesarReceta(Request $request){
        $this->crearNuevaReceta();
        $this->introducirCedulaProfesional("12345678");
        $this->introducirMedicamento("Paracetamol 500mg",7);

        $numTarjeta = "1234 1234 1234 1234"; 
        
        $resultado = $this->recetaService->procesarReceta($numTarjeta);
        
        $fechaRecoleccion = now()->addDay()->setTime(10, 0)->format('d/m/Y H:i');
        if($resultado == true){
            return view('tas.resultado', [
                'exito' => true,
                'numeroPedido' => 1,
                'cedulaProfesional' => "12345678",
                'farmacia' => "Farmacias del Ahorro" . ' - Sucursal Cedros',
                'medicamentos' => [],
                'fechaRecoleccion' => $fechaRecoleccion,
                'mensaje' => 'Receta procesada correctamente'
            ]);
        }
        return view('tas.resultado', [
            'exito' => false,
            'mensaje' => "No se pudo surtir todos los medicamentos",
            'cedulaProfesional' => $request->cedula_profesional ?? 'N/A',
            'farmacia' => ($request->farmacia_cadena ?? 'N/A') . ' - Sucursal ' . ($request->farmacia_sucursal ?? 'N/A'),
            'medicamentos' => []
        ]);
    }
}