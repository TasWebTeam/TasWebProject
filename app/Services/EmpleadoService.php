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

    /**
     * Listar recetas de la sucursal del empleado actual
     */
    public function obtenerRecetasEmpleado(string $idCadena,int $idSucursal,?string $estado): array
    {
        //$consultarRepository = new ConsultarRepository();
        //$actualizarRepository = new ActualizarRepository();
        $recetasModel = $this->repo->obtenerPorSucursal($idCadena,$idSucursal,$estado);
        //dd($recetasModel);
        $recetas = [];
        foreach ($recetasModel as $r) {
            $recetas[] = $this->mapToDomain($r);
        }

        return $recetas;
    }

    /**
     * Recetas expiradas
     */
    public function obtenerRecetasExpiradas(string $idCadena,int $idSucursal): array
    {
        $recetasModel = $this->repo->obtenerExpiradasPorSucursal($idCadena,$idSucursal);

        $recetas = [];
        foreach ($recetasModel as $r) {
            $recetas[] = $this->mapToDomain($r);
        }

        return $recetas;
    }

    /**
     * Cambiar estado de receta
     */
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

    /**
     * Mapear RecetaModel → Receta (dominio)
     */
    /*private function mapToDomain($model): Receta
    {
        return new Receta(
            $model->id_receta,
            null,  // sucursal se puede mapear luego
            $model->cedula_profesional,
            new DateTime($model->fecha_registro),
            new DateTime($model->fecha_recoleccion),
            $model->estado_pedido,
            [], // detalles llenar después
            null
        );
    }*/
        /**
     * Mapear RecetaModel → Receta (dominio)
     */
    private function mapToDomain($model): Receta
    {
        // 1) Sucursal de destino → dominio (opcional)
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

        // 2) Fechas que pueden venir null
        $fechaRegistro    = $model->fecha_registro
            ? new DateTime($model->fecha_registro)
            : null;

        $fechaRecoleccion = $model->fecha_recoleccion
            ? new DateTime($model->fecha_recoleccion)
            : null;

        // 3) Construimos la Receta de dominio
        return new Receta(
            $model->id_receta,
            $sucursalDomain,
            $model->cedula_profesional ?? '',
            $fechaRegistro,
            $fechaRecoleccion,
            $model->estado_pedido ?? '',
            [],   // más adelante mapeas DetalleReceta si lo necesitas
            null  // más adelante mapeas Pago si lo necesitas
        );
    }


        /**
     * Obtener Sucursal (dominio) a partir de idCadena + idSucursal
     */
    public function obtenerSucursalPorCadenaYSucursal(String $idCadena, int $idSucursal): ?Sucursal
    {
        $sucursalModel = $this->repo->obtenerSucursalPorCadenaYSucursal($idCadena, $idSucursal);

        if (!$sucursalModel) {
            return null;
        }

        // Dominio Cadena
        $cadenaDomain = null;
        if ($sucursalModel->cadena) {
            $cadenaModel = $sucursalModel->cadena;
            $cadenaDomain = new Cadena(
                $cadenaModel->id_cadena,
                $cadenaModel->nombre
            );
        }

        // Dominio Sucursal
        return new Sucursal(
            $sucursalModel->id_sucursal,
            $cadenaDomain,
            $sucursalModel->nombre,
            $sucursalModel->latitud,
            $sucursalModel->longitud
        );
    }

      public function construirSegmentosMapaReceta(int $idReceta): array
    {
        // 1) Recuperar receta de dominio
        $receta = $this->consultarRepository->recuperarReceta($idReceta);

        $sucDestino = $receta->getSucursal();
        $segmentos = [];

        foreach ($receta->getDetallesReceta() as $detalle) {
            foreach ($detalle->getLineasSurtido() as $linea) {
                $sucursalOrigen = $linea->getSucursal();

                // 2) Pedir ruta a ORS mediante tu service
                $ruta = $this->rutaService->obtenerRutaCoordenadas(
                    $sucursalOrigen->getLatitud(),
                    $sucursalOrigen->getLongitud(),
                    $sucDestino->getLatitud(),
                    $sucDestino->getLongitud()
                );

                // 3) Armar DTO para la vista
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
                    'ruta' => $ruta, // array de puntos [ ['lat'=>..,'lng'=>..], ... ]
                ];
            }
        }
        // DEBUG para ver qué se manda finalmente
        // dd($segmentos);
        return $segmentos;
    }


}
