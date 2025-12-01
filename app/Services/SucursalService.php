<?php

namespace App\Services;

use App\Domain\Sucursal;
use App\Domain\Medicamento;
use App\Repositories\ConsultarRepository;

class SucursalService
{
    private int $limiteBD;
    private int $maxCandidatosOSM;
    private float $radioKm;

    private ConsultarRepository $consultarRepo;
    private RutaOpenStreetMapService $rutaService;

    public function __construct(
        ConsultarRepository $consultarRepo,
        RutaOpenStreetMapService $rutaService,
        int $limiteBD = 4,          // máximo de sucursales que trae de la BD
    ) {
        $this->consultarRepo    = $consultarRepo;
        $this->rutaService      = $rutaService;
        $this->limiteBD         = $limiteBD;
    }
    
    public function obtenerSucursalesOrdenadasPorDistanciaYConStock(
        Sucursal $sucursalDestino,
        Medicamento $medicamento,
        $cantidadRequerida
    ): array {
        $limiteBD = max($this->limiteBD, $cantidadRequerida);
        // 1) Buscar sucursales candidatas usando Haversine en BD
        $candidatas = $this->consultarRepo->buscarSucursalesCandidatas(
            $sucursalDestino,
            $medicamento,
            $limiteBD
            //radioKm: $this->radioKm
        );

        if (empty($candidatas)) {
            return [];
        }

        // Coordenadas del DESTINO (la sucursal que solicita surtido)
        $latD = $sucursalDestino->getLatitud();
        $lonD = $sucursalDestino->getLongitud();

        $items = [];

        foreach ($candidatas as $sucOrigen) {

            /** @var Sucursal $sucOrigen */

            // 2) Calcular distancia REAL con OSM: ORIGEN -> DESTINO
            $distKm = $this->rutaService->obtenerDistanciaKm(
                $sucOrigen->getLatitud(),
                $sucOrigen->getLongitud(),
                $latD,
                $lonD
            );

            // Si la API falla → descartar (o poner un fallback)
            if ($distKm === null) {
                continue;
            }

            // 3) STOCK REAL desde dominio
            // $stock = $sucOrigen->verificarDisponibilidad(PHP_INT_MAX, $medicamento);
            // if ($stock <= 0) continue;

            // 4) Agregar al arreglo
            $items[] = [
                'sucursal'     => $sucOrigen,
                'distancia_km' => $distKm,
                // 'stock'        => $stock,
            ];
        }

        // 5) Ordenar por distancia real
        usort($items, fn($a, $b) => $a['distancia_km'] <=> $b['distancia_km']);
        return $items;
    }

    

//    public function obtenerRutaOptimizadaDesdeItems(
//         Sucursal $sucursalDestino,
//         array $items
//     ): array {
//         if (empty($items)) {
//             return [];
//         }

//         // 1) construir jobs y mapa jobId -> sucursal
//         $jobs          = [];
//         $jobToSucursal = [];
//         $jobId         = 1;

//         foreach ($items as $item) {
//             if (!isset($item['sucursal'])) {
//                 continue;
//             }

//             /** @var Sucursal $suc */
//             $suc = $item['sucursal'];

//             $jobs[] = [
//                 'id'       => $jobId,
//                 'location' => [$suc->getLongitud(), $suc->getLatitud()], // [lon, lat]
//             ];

//             $jobToSucursal[$jobId] = $suc;
//             $jobId++;
//         }

//         if (empty($jobs)) {
//             return [];
//         }

//         // 2) vehículo que sale/regresa a la sucursal destino
//         $vehicles = [[
//             'id'    => 1,
//             'start' => [$sucursalDestino->getLongitud(), $sucursalDestino->getLatitud()],
//             'end'   => [$sucursalDestino->getLongitud(), $sucursalDestino->getLatitud()],
//         ]];

//         // 3) llamar a Optimization Service
//         $data = $this->rutaService->optimizarRutaORS($jobs, $vehicles);

//         if ($data === null || empty($data['routes'][0]['steps'])) {
//             // Fallback: regresar en el orden original
//             return array_map(fn($it) => $it['sucursal'], $items);
//         }

//         // 4) construir la ruta de sucursales en orden
//         $ruta = [];

//         foreach ($data['routes'][0]['steps'] as $step) {
//             if (!isset($step['job'])) {
//                 continue;
//             }

//             $idJob = $step['job'];

//             if (isset($jobToSucursal[$idJob])) {
//                 $ruta[] = $jobToSucursal[$idJob];
//             }
//         }

//         return $ruta;
//     }
}