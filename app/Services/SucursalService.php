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
    public function obtenerSucursalMasCercanaConStock(
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

        return $mejorSuc;
    }
}