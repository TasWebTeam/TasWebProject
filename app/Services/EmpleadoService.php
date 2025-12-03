<?php

namespace App\Services;

use App\Repositories\EmpleadoRepository;
use App\Domain\Receta;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use App\Repositories\ActualizarRepository;
use App\Repositories\ConsultarRepository;
use App\Services\RutaOpenStreetMapService;
use DateTime;

class EmpleadoService
{
    private EmpleadoRepository $repo;
    private ConsultarRepository $consultarRepository;
    private RutaOpenStreetMapService $rutaService;

    public function __construct(EmpleadoRepository $repo , ConsultarRepository $consultarRepository, RutaOpenStreetMapService $rutaService)
    {
        $this->repo = $repo;
        $this->consultarRepository = $consultarRepository;
        $this->rutaService = $rutaService;
    }


    public function obtenerRecetasEmpleado(string $idCadena,int $idSucursal,?string $estado): array
    {
        $recetasModel = $this->repo->obtenerPorSucursal($idCadena,$idSucursal,$estado);
        $recetas = [];
        foreach ($recetasModel as $r) {
            $recetas[] = $this->mapToDomain($r);
        }
        return $recetas;
    }

    public function obtenerRecetasExpiradas(string $idCadena,int $idSucursal): array
    {
        $recetasModel = $this->repo->obtenerExpiradasPorSucursal($idCadena,$idSucursal);

        $recetas = [];
        foreach ($recetasModel as $r) {
            $recetas[] = $this->mapToDomain($r);
        }

        return $recetas;
    }


    public function actualizarEstado(int $idReceta, string $estado): bool
    {
        return $this->repo->actualizarEstado($idReceta, $estado);
    }


    public function marcarComoLista(int $idReceta): bool
    {
        return $this->repo->actualizarEstado($idReceta, 'lista_para_recoleccion');
    }

    public function marcarComoEntregada(int $idReceta): bool
    {
        return $this->repo->actualizarEstado($idReceta, 'entregada');
    }

    private function mapToDomain($model): Receta
    {
        $sucursalDomain = null;

        if ($model->sucursalDestino) {
            $suc = $model->sucursalDestino;

            $cadenaDomain = null;
            if ($suc->cadena) {
                $cad = $suc->cadena;
                $cadenaDomain = new Cadena(
                    $cad->id_cadena,
                    $cad->nombre
                );
            }

            $sucursalDomain = new Sucursal(
                $suc->id,
                $cadenaDomain,
                $suc->id_sucursal,
                $suc->nombre,
                $suc->latitud,
                $suc->longitud
            );
        }

        $fechaRegistro    = $model->fecha_registro
            ? new DateTime($model->fecha_registro)
            : null;

        $fechaRecoleccion = $model->fecha_recoleccion
            ? new DateTime($model->fecha_recoleccion)
            : null;

        return new Receta(
            $model->id_receta,
            $sucursalDomain,
            $model->cedula_profesional ?? '',
            $fechaRegistro,
            $fechaRecoleccion,
            $model->estado_pedido ?? '',
            [],   
            null 
        );
    }

    public function obtenerSucursalPorCadenaYSucursal(String $idCadena, int $idSucursal): ?Sucursal
    {
        $sucursalModel = $this->repo->obtenerSucursalPorCadenaYSucursal($idCadena, $idSucursal);

        if (!$sucursalModel) {
            return null;
        }

        $cadenaDomain = null;
        if ($sucursalModel->cadena) {
            $cadenaModel = $sucursalModel->cadena;
            $cadenaDomain = new Cadena(
                $cadenaModel->id_cadena,
                $cadenaModel->nombre
            );
        }

        return new Sucursal(
            $sucursalModel->id_sucursal,
            $cadenaDomain,
            $sucursalModel->nombre,
            $sucursalModel->latitud,
            $sucursalModel->longitud
        );
    }

    public function obtenerRecetaYDetallesResumen(int $idReceta): array
    {
        $receta = $this->consultarRepository->recuperarReceta($idReceta);

        $detallesResumen = [];

        foreach ($receta->getDetallesReceta() as $detalle) {
            $fila = [
                'medicamento'    => $detalle->getMedicamento()->getNombre(),
                'cantidadTotal'  => $detalle->getCantidad(),
                'precioUnitario' => $detalle->getPrecio(),
                'subtotal'       => $detalle->obtenerSubtotal(),
                'surtidos'       => [],
            ];

            foreach ($detalle->getLineasSurtido() as $linea) {
                $sucursal = $linea->getSucursal();
                $fila['surtidos'][] = [
                    'sucursal' => $sucursal->getNombre(),
                    'cadena'   => $sucursal->getCadena()->getNombre(),
                    'cantidad' => $linea->getCantidad(),
                ];
            }

            $detallesResumen[] = $fila;
        }
        $totalGeneral = $receta->calcularTotal();
        $comision = $receta->calcularComision($totalGeneral);
        $totalConComision = $totalGeneral + $comision;

        return [
            'receta'          => $receta,
            'detallesResumen' => $detallesResumen,
            'totalGeneral'      => $totalGeneral,
            'comision'          => $comision,
            'totalConComision'  => $totalConComision
        ];
    }

    public function construirSegmentosMapaReceta(int $idReceta): array
    {
        $receta = $this->consultarRepository->recuperarReceta($idReceta);

        $sucDestino = $receta->getSucursal();
        $segmentos = [];

        foreach ($receta->getDetallesReceta() as $detalle) {
            foreach ($detalle->getLineasSurtido() as $linea) {
                $sucursalOrigen = $linea->getSucursal();

                $ruta = $this->rutaService->obtenerRutaCoordenadas(
                    $sucursalOrigen->getLatitud(),
                    $sucursalOrigen->getLongitud(),
                    $sucDestino->getLatitud(),
                    $sucDestino->getLongitud()
                );

                $segmentos[] = [
                    'origen' => [
                        'lat'    => $sucursalOrigen->getLatitud(),
                        'lng'    => $sucursalOrigen->getLongitud(),
                        'nombre' => $sucursalOrigen->getNombre(),
                        'cadena' => $sucursalOrigen->getCadena()->getNombre(),
                    ],
                    'destino' => [
                        'lat'    => $sucDestino->getLatitud(),
                        'lng'    => $sucDestino->getLongitud(),
                        'nombre' => $sucDestino->getNombre(),
                        'cadena' => $sucDestino->getCadena()->getNombre(),
                    ],
                    'ruta' => $ruta,
                ];
            }
        }
        return $segmentos;
    }
}