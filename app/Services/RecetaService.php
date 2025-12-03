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
use Illuminate\Support\Facades\Log;

class RecetaService 
{
    private TasService $tasService;
    private ?Paciente $paciente = null;
    private ConsultarRepository $consultarRepository;
    private ActualizarRepository $actualizarRepository;
    private SucursalService $sucursalService;

    public function __construct(
        TasService $tasService, 
        ConsultarRepository $consultarRepository, 
        ActualizarRepository $actualizarRepository,
        SucursalService $sucursalService
    ) {
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
        
        Log::info('Nueva receta creada para paciente', [
            'paciente_id' => $paciente->getId(),
            'correo' => $correo
        ]);
    }

    public function introducirSucursal($nombreCadena, $nombreSucursal) 
    {
        
        $rec = $this->paciente->getUltimaReceta();
        $cad = $this->consultarRepository->recuperarCadena($nombreCadena);
        $suc = $this->consultarRepository->recuperarSucursal($nombreSucursal, $cad);
        $rec->asignarSucursal($suc);
        
        Log::info('Sucursal asignada a receta', [
            'cadena' => $nombreCadena,
            'sucursal' => $nombreSucursal
        ]);
    }

    public function introducirCedulaProfesional($cedulaProfesional)
    {
        
        $rec = $this->paciente->getUltimaReceta();
        $rec->introducirCedulaProfesional($cedulaProfesional);
        
        Log::info('Cédula profesional introducida', [
            'cedula' => $cedulaProfesional
        ]);
    }

    public function introducirMedicamento($nombreMedicamento, $cantidad)
    {
        try {
            
            Log::info('Introduciendo medicamento', [
                'medicamento' => $nombreMedicamento,
                'cantidad' => $cantidad
            ]);
            
            $rec = $this->paciente->getUltimaReceta();
            $suc = $rec->getSucursal();

            if (!$suc) {
                throw new \Exception('No hay sucursal asignada a la receta');
            }

            $cadena = $suc->getCadena();
            $idSucursal = $suc->getId(); // ✅ USAR getIdSucursal()

            Log::info('Buscando medicamento en inventario', [
                'cadena' => $cadena->getIdCadena(),
                'sucursal_id' => $idSucursal,
                'medicamento' => $nombreMedicamento
            ]);

            // Usar recuperarInventarioConsultar (sin lock) para agregar medicamentos
            $inv = $this->consultarRepository->recuperarInventarioConsultar($cadena, $idSucursal, $nombreMedicamento);
            
            $med = $inv->obtenerMedicamento();
            $precio = $inv->obtenerPrecio();

            $rec->crearDetalleReceta($med, $cantidad, $precio);
            $total = $rec->calcularTotal();
            $comisionTotal = $rec->calcularComision($total);
            $rec->obtenerPago()->actualizarComision($comisionTotal);
            
            Log::info('Medicamento agregado exitosamente', [
                'medicamento' => $nombreMedicamento,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'total_receta' => $total,
                'comision' => $comisionTotal
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al introducir medicamento', [
                'error' => $e->getMessage(),
                'medicamento' => $nombreMedicamento,
                'cantidad' => $cantidad,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    public function procesarReceta($numTarjeta)
    {
        $rec = $this->paciente->getUltimaReceta();
        
        $this->actualizarRepository->beginTransaction();
        
        try {
            $sucursalOrigen = $rec->getSucursal();
            $detallesReceta = $rec->getDetallesReceta();
            foreach($detallesReceta as $detalle) {
                $resultado = $this->procesarDetalle($detalle, $sucursalOrigen);
                
                if ($resultado === false) {
                    $this->actualizarRepository->rollbackTransaction();
                    return false;
                }
            }
            
            // Validar pago
            $pago = $rec->obtenerPago();
            $pago->validarPago((string)$numTarjeta);
            // Calcular fecha de recolección
            $rec->calcularFecha();
            
            // Cambiar estado
            $rec->cambiarEstado('en_proceso');

            // Guardar todo en la base de datos
            $this->actualizarRepository->guardarRecetaCompleta($this->paciente, $rec);
            
            $this->actualizarRepository->commitTransaction();
            
            return true;
            
        } catch (\Throwable $e) {
            $this->actualizarRepository->rollbackTransaction();
            return false;            
        }
    }

    /**
     * Procesa un detalle de la receta, intentando surtir el medicamento
     * desde la sucursal origen y otras sucursales si es necesario
     */
    private function procesarDetalle(DetalleReceta $detalle, Sucursal $sucursalOrigen)
    {
        $cantidadRequerida = $detalle->getCantidad();
        $sucursalActual    = $sucursalOrigen;

    // $rec = $this->paciente->getUltimaReceta();

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
                return false;
            }

            $infoSucursal   = array_shift($candidatas);
            $sucursalActual = $infoSucursal['sucursal'];
        }
    }

    /**
     * Verifica la disponibilidad de un medicamento en una sucursal
     * y descuenta del inventario si hay stock disponible
     */
    private function verificarDisponibilidadEnSucursal(
        Sucursal $sucursal,
        Medicamento $medicamento,
        int $cantidadSolicitada
    ): int {
        try {

            $idSucursal = $sucursal->getId(); 

            $inv = $this->consultarRepository->recuperarInventario(
                $sucursal->getCadena(),
                $idSucursal, 
                $medicamento->getNombre()
            );
            
            $stockExistente = $inv->obtenerStock();
            $cantObtenida = 0;

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
                    $idSucursal,
                    $inv
                );
            } 
            return $cantObtenida;
        } catch (\Exception $e) {
            return 0;
        }
    }
}