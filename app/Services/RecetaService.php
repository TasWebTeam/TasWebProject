<?php

namespace App\Services;

use App\Services\TasService;
use App\Domain\Paciente;
use App\Domain\Sucursal;
use App\Domain\Medicamento;
use App\Domain\DetalleReceta;
use App\Domain\InventarioSucursal;

use App\Repositories\ActualizarRepository;
use App\Repositories\ConsultarRepository;

class RecetaService 
{
    private TasService $tasService;
    private Paciente $paciente;
    private ConsultarRepository $consultarRepository;
    private ActualizarRepository $actualizarRepository;

    private SucursalService $sucursalService;

    public function __construct(
        TasService $tasService, 
        ConsultarRepository $consultarRepository, 
        ActualizarRepository $actualizarRepository,
        SucursalService $sucursalService)
    {
        $this->tasService = $tasService;
        $this->consultarRepository = $consultarRepository;
        $this->actualizarRepository = $actualizarRepository;
        $this->sucursalService = $sucursalService;
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

        $cadena = $suc->getCadena();
        $id = $suc->getId();

        $inv = $this->consultarRepository->recuperarInventario($cadena, $id, $nombreMedicamento);
        $med = $inv->obtenerMedicamento();
        $precio = $inv->obtenerPrecio();

        $rec->crearDetalleReceta($med, $cantidad, $precio);
        $total = $rec->calcularTotal();
        $comisionTotal = $rec->calcularComision($total);
        $rec->obtenerPago()->actualizarComision($comisionTotal);
    }
    
    public function procesarReceta($numTarjeta){
        
        $this->actualizarRepository->beginTransaction();
        $rec = $this->paciente->getUltimaReceta();
        try {
            $sucursalOrigen = $rec->getSucursal();

            foreach ($rec->getDetallesReceta() as $detalle) {
                $this->procesarDetalle($detalle, $sucursalOrigen);
            }

            // Validar pago en dominio
            $rec->obtenerPago()->validarPago((string)$numTarjeta);
            dd($rec);
            // Calcular fecha de recolección (dominio)
            $rec->calcularFecha();

            // (Opcional) Cambiar estado
            $rec->cambiarEstado('En preparacion');

            $this->actualizarRepository->guardarRecetaCompleta($this->paciente, $rec);

            $this->actualizarRepository->commitTransaction();
        } catch (\Throwable $e) {
            $this->actualizarRepository->rollbackTransaction();
            throw $e;
        }
    }

    private function procesarDetalle(DetalleReceta $detalle, Sucursal $sucursalOrigen): void
    {
        $cantidadRequerida = $detalle->getCantidad();
        $sucursalActual    = $sucursalOrigen;

        $rec = $this->paciente->getUltimaReceta();

        $buscarSucursales = true;
        $candidatas       = [];

        while ($cantidadRequerida > 0) {

            $cantObtenida = $this->verificarDisponibilidadEnSucursal(
                $sucursalActual,
                $detalle->getMedicamento(),
                $cantidadRequerida
            );

            if ($cantObtenida > 0) {
                $estado = "Distribuida";
                $detalle->registrarSurtido($sucursalActual, $cantObtenida, $estado);
                $cantidadRequerida -= $cantObtenida;
            }

            if ($cantidadRequerida <= 0) {
                break;
            }

            if ($buscarSucursales) {
                $buscarSucursales = false;
                $candidatas = $this->sucursalService
                    ->obtenerSucursalesOrdenadasPorDistanciaYConStock(
                        $sucursalOrigen,
                        $detalle->getMedicamento(),
                        $cantidadRequerida
                    );
            }

            if (empty($candidatas)) {
                throw new \RuntimeException(
                    "No se pudo surtir el medicamento " . $detalle->getMedicamento()->getNombre()
                );
            }

            $infoSucursal   = array_shift($candidatas);
            $sucursalActual = $infoSucursal['sucursal'];
        }
    }

    private function verificarDisponibilidadEnSucursal(
        Sucursal $sucursal,
        Medicamento $medicamento,
        int $cantidadSolicitada
    ): int {
        $inv = $this->consultarRepository->recuperarInventario(
            $sucursal->getCadena(),
            $sucursal->getId(),
            $medicamento->getNombre()
        );
        $stockExistente = $inv->obtenerStock();
        $cantObtenida   = 0;

        if ($stockExistente > 0) {
            if ($stockExistente >= $cantidadSolicitada) {
                $inv->descontarMedicamento($cantidadSolicitada);
                $cantObtenida = $cantidadSolicitada;
            } else {
                $inv->descontarMedicamento($stockExistente);
                $cantObtenida = $stockExistente;
            }

            $this->actualizarRepository->actualizarInventario(
                $sucursal->getCadena(),
                $sucursal->getIdSucursal(),
                $inv
            );
        }
        
        return $cantObtenida;
    }
}