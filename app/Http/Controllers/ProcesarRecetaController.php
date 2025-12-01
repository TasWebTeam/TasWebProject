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
        $usuarioCorreo= session('usuario.correo');      // no puede ser por id?
        $this->recetaService->crearNuevaReceta($usuarioCorreo);

        // ESTO NO VA ----
        $cadena = "Farmacias Benavides";
        $sucursal = "Pedro Anaya";

        $this->introducirSucursal($cadena, $sucursal);  // quitar
    }

    public function introducirSucursal(string $nombreCadena, string $nombreSucursal){
        $this->recetaService->introducirSucursal($nombreCadena, $nombreSucursal);

        // ESTO NO VA ----
        $this->introducirCedulaProfesional("123456");
    }

    public function introducirCedulaProfesional(string $cedulaProfesional){
        
        $this->recetaService->introducirCedulaProfesional($cedulaProfesional);

        // ESTO NO VA ----
        $this->introducirMedicamento("Paracetamol", 5);
    }
    
    public function introducirMedicamento(string $nombreMedicamento, int $cantidad){

        $this->recetaService->introducirMedicamento($nombreMedicamento, $cantidad);

        $this->TESTING();
    }

    public function procesarReceta(int $numTarjeta){
        
    }
    
    public function TESTING(){
        // Aqui tengo que agregar objetos de tipo medicamento
        // Cadena
        // id - nombre
        $cadena1 = new Cadena("BNV", "Farmacias Benavides");
        // Sucursal
        // id - Cadena - nombre - latitud - longitud
        $sucursal1 = new Sucursal(4, $cadena1, 2, "Pedro Anaya", 24.82146940, -107.38997500);
        // Pago
        $pago = new Pago();
        // Receta 1
        $date = new DateTime();
        $receta1 = new Receta(1, $sucursal1, "123456", $date, $date, "Pedido", [], $pago);
        // Medicamentos
        // id - nombre - especificacion - laboratorio
        $medicamento1 = new Medicamento(1, "Paracetamol", 'Tabletas 500 mg', "Genfar");
        $medicamento2 = new Medicamento(2, "Ibuprofeno", 'Capsulas 400 mg', "Bayer");
        
        // DetallesReceta
        $detalle1 = new DetalleReceta($medicamento1, 10, 10.0, []);
        // $detalle2 = new DetalleReceta($medicamento2, 10, 20.0, []);

        // Agregar a receta los detalles
        $receta1->agregarDetalleReceta($detalle1);
        //$receta1->agregarDetalleReceta($detalle2);
        // Procesarlo
        $receta1->procesarReceta("1234", $this->sucursalService);
        dd($receta1->getDetallesReceta());
    }
}