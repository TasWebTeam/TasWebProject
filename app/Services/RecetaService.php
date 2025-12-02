<?php

namespace App\Services;

use App\Services\TasService;
use App\Domain\Paciente;
use App\Repositories\ConsultarRepository;

class RecetaService 
{
    private TasService $tasService;
    private Paciente $paciente;
    private ConsultarRepository $consultarRepository;

    public function __construct(TasService $tasService, ConsultarRepository $consultarRepository)
    {
        $this->tasService = $tasService;
        $this->consultarRepository = $consultarRepository;
    }

    public function obtenerPaciente($correo)
    {
        $usuario = $this->tasService->encontrarUsuario($correo);
        $paciente = new Paciente(
            $usuario->getId(),
            $usuario->getNombre(),
            $usuario->getApellido(),
            $usuario->getCorreo(),
            $usuario->getNip()
        );
        $this->paciente = $paciente;
        return $paciente;
    }

    public function crearNuevaReceta($correo)
    {
        $paciente = $this->obtenerPaciente($correo);
        $paciente->crearNuevaReceta();
    }

    public function introducirSucursal($nombreCadena, $nombreSucursal) // no sera mejor con el id del paciente?
    {
        $rec = $this->paciente->getUltimaReceta();
        $cad = $this->consultarRepository->recuperarCadena($nombreCadena);
        $suc = $this->consultarRepository->recuperarSucursal($nombreSucursal, $cad);
        $rec->asignarSucursal($suc);
    }

    public function introducirCedulaProfesional($cedulaProfesional)
    {
        $rec = $this->paciente->getUltimaReceta();
        $rec->introducirCedulaProfesional($cedulaProfesional);
    }

    public function introducirMedicamento($nombreMedicamento, $cantidad){
        $rec = $this->paciente->getUltimaReceta();
        $suc = $rec->getSucursal();

        $inv = $suc->obtenerInventario($nombreMedicamento);
        
        $med = $inv->obtenerMedicamento();
        $precio = $inv->obtenerPrecio();

        $rec->crearDetalleReceta($med, $cantidad, $precio);
        $total = $rec->calcularTotal();
        $comisionTotal = $rec->calcularComision($total);
        $rec->obtenerPago()->actualizarComision($comisionTotal);
        // que lo busque en el repository y lo traiga

        // que lo busque en el inventario de la sucursal y traiga el precio

        // luego receta crea un nuevo detalle receta con
        // el medicamento, cantidad y precio y lo guarda

        // se calcula el pago, y se actualiza
        // $rec->introducirMedicamento($nombreMedicamento, $cantidad);
    }
}

