<?php

namespace App\Services;

use App\Domain\Sucursal;
use App\Domain\Medicamento;
use App\repositories\ConsultarRepository;

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
        int $limiteBD = 20,          // máximo de sucursales que trae de la BD
        int $maxCandidatosOSM = 5,   // máximo de sucursales a las que se les pide ruta OSM
        float $radioKm = 20.0        // radio aproximado de búsqueda para el bounding box
    ) {
        $this->consultarRepo    = $consultarRepo;
        $this->rutaService      = $rutaService;
        $this->limiteBD         = $limiteBD;
        $this->maxCandidatosOSM = $maxCandidatosOSM;
        $this->radioKm          = $radioKm;
    }

    /**
     * Devuelve la sucursal más cercana que tenga stock del medicamento.
     *
     * Flujo:
     *  1) La BD trae solo sucursales CANDIDATAS:
     *     - misma cadena
     *     - dentro de un bounding box alrededor de la sucursal origen
     *     - con el medicamento y stock_actual > 0
     *     - ordenadas por distancia Haversine (en SQL)
     *     - limitadas a $limiteBD
     *  2) Sobre esas, se toma el top N ($maxCandidatosOSM) y se mide
     *     la distancia de ruta real usando OSM.
     *  3) Se regresa la sucursal con menor distancia OSM.
     */
    public function obtenerSucursalMasCercanaConStock(  // YA NO SE USA
        Sucursal $sucursalOrigen,
        Medicamento $medicamento,
        int $cantidadRequerida
    ): ?Sucursal {
        // 1) Candidatas desde BD (ya filtradas y ordenadas por Haversine)

        $candidatas = $this->consultarRepo->buscarSucursalesCandidatas(
            $sucursalOrigen,
            $medicamento,
            $cantidadRequerida,
            $this->limiteBD,
            $this->radioKm
        );

        if (empty($candidatas)) {
            return null;
        }
        
        $latO = $sucursalOrigen->getLatitud();
        $lonO = $sucursalOrigen->getLongitud();
        
        // 2) Afinar con OSM sobre las N más cercanas
        $topCandidatas = array_slice($candidatas, 0, $this->maxCandidatosOSM);

        $mejorSuc    = null;
        $mejorDistKm = INF;

        foreach ($topCandidatas as $suc) {
            /** @var Sucursal $suc */

            $distKm = $this->rutaService->obtenerDistanciaKm(
                $latO,
                $lonO,
                $suc->getLatitud(),
                $suc->getLongitud()
            );

            if ($distKm === null) {
                continue;
            }

            if ($distKm < $mejorDistKm) {
                $mejorDistKm = $distKm;
                $mejorSuc    = $suc;
            }
        }
        return $mejorSuc;   // TE REGRESA LA MEJOR SUCURSAL SEGUN HARVENSINE Y OSM
    }

    public function obtenerSucursalesOrdenadasPorDistanciaReal(
    Sucursal $sucursalOrigen,
    Medicamento $medicamento,
    $cantidadRequerida
): array {
    // 1) Candidatas desde BD (Haversine)
    $candidatas = $this->consultarRepo->buscarSucursalesCandidatas(
        $sucursalOrigen,
        $medicamento,
        limite: $this->limiteBD
    );

    if (empty($candidatas)) {
        return [];
    }

    $latO = $sucursalOrigen->getLatitud();
    $lonO = $sucursalOrigen->getLongitud();

    $items = [];

    // 2) Calculas distancia OSM para las candidatas
    foreach ($candidatas as $suc) {
        /** @var Sucursal $suc */

        $distKm = $this->rutaService->obtenerDistanciaKm(
            $latO,
            $lonO,
            $suc->getLatitud(),
            $suc->getLongitud()
        );

        // Si OSM falla, puedes saltar o usar fallback (Haversine)
        if ($distKm === null) {
            // opcional: fallback
            // $distKm = $this->distanciaHaversine(...);
            continue;
        }

        $items[] = [
            'sucursal'     => $suc,
            'distancia_km' => $distKm,
        ];
    }

    // 3) Ordenar por distancia real
    usort($items, function ($a, $b) {
        return $a['distancia_km'] <=> $b['distancia_km'];
    });

    // 4) Devolver solo las sucursales (o el array con distancias si te sirve)
    return array_map(
        fn ($item) => $item['sucursal'],
        $items
    );
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
}