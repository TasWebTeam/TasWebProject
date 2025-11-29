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
use DateTime;

class ProcesarRecetaController extends Controller
{
    private SucursalService $sucursalService; 
    public function __construct(SucursalService $sucursalService){
        $this->sucursalService = $sucursalService;
    }
    public function crearNuevaReceta(){
        // $paciente = new Paciente();
    }

    public function introducirSucursal(string $nombreSucursal, string $nombreCadena){

    }

    public function introducirCedulaProfesional(string $cedulaProfesional){

    }

    public function introducirMedicamento(string $nombreMedicamento, int $cantidad){

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
        $sucursal1 = new Sucursal(2, $cadena1, "Pedro Anaya", 24.82146940, -107.38997500);
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
        $detalle1 = new DetalleReceta($medicamento1, 5, 10.0, []);
        $detalle2 = new DetalleReceta($medicamento2, 10, 20.0, []);

        // Agregar a receta los detalles
        $receta1->agregarDetalleReceta($detalle1);
        //$receta1->agregarDetalleReceta($detalle2);
        // Procesarlo
        $receta1->procesarReceta("1234", $this->sucursalService);
    }
}