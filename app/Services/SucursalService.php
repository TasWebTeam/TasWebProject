<?php

namespace App\Services;

use App\Domain\Sucursal;
use App\Domain\Medicamento;
use App\Domain\Receta;
use App\Repositories\ConsultarRepository;
use App\Repositories\ActualizarRepository;

class SucursalService
{
    private int $limiteBD;
    private int $maxCandidatosOSM;
    private float $radioKm;

    private ConsultarRepository $consultarRepo;
    private ActualizarRepository $actualizarRepo;
    private RutaOpenStreetMapService $rutaService;

    public function __construct(
        ConsultarRepository $consultarRepo,
        RutaOpenStreetMapService $rutaService,
        ActualizarRepository $actualizarRepo,

        int $limiteBD = 4,          
    ) {
        $this->consultarRepo    = $consultarRepo;
        $this->actualizarRepo = $actualizarRepo;
        $this->rutaService      = $rutaService;
        $this->limiteBD         = $limiteBD;
    }
    
    public function obtenerSucursalesOrdenadasPorDistanciaYConStock(
        Sucursal $sucursalDestino,
        Medicamento $medicamento,
        $cantidadRequerida
    ): array {
        $limiteBD = max($this->limiteBD, $cantidadRequerida);
        $candidatas = $this->consultarRepo->buscarSucursalesCandidatas(
            $sucursalDestino,
            $medicamento,
            $limiteBD
        );

        if (empty($candidatas)) {
            return [];
        }

        $latD = $sucursalDestino->getLatitud();
        $lonD = $sucursalDestino->getLongitud();

        $items = [];

        foreach ($candidatas as $sucOrigen) {

            $distKm = $this->rutaService->obtenerDistanciaKm(
                $sucOrigen->getLatitud(),
                $sucOrigen->getLongitud(),
                $latD,
                $lonD
            );

            if ($distKm === null) {
                continue;
            }

            $items[] = [
                'sucursal'     => $sucOrigen,
                'distancia_km' => $distKm,
            ];
        }

        usort($items, fn($a, $b) => $a['distancia_km'] <=> $b['distancia_km']);
        return $items;
    }

    public function devolverReceta(int $idReceta): bool
    {
        try {
            $receta = $this->consultarRepo->recuperarReceta($idReceta);

            $this->actualizarRepo->beginTransaction();

            $this->devolverLineasASucursal($receta);

            $this->actualizarRepo->commitTransaction();

            return true;
        } catch (\Throwable $e) {
            $this->actualizarRepo->rollbackTransaction();
            return false;
        }
    }

    private function devolverLineasASucursal($receta): void
    {
         foreach ($receta->getDetallesReceta() as $detalle){
                foreach ($detalle->getLineasSurtido() as $linea) {
                $cadena      = $receta->getSucursal()->getCadena();
                $idSucursal    = $receta->getSucursal()->getId();
                $medicamento = $detalle->getMedicamento();
                $cantidad      = $linea->getCantidad();

                $inventario = $this->consultarRepo->recuperarInventario(
                    $cadena,
                    $idSucursal,
                    $medicamento->getNombre()
                );

                $inventario->devolverMedicamento($cantidad);

                $this->actualizarRepo->actualizarInventario(
                    $cadena,       
                    $idSucursal,   
                    $inventario                         
                );
            }
        }
    }
}