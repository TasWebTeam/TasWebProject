<?php

namespace App\Services;

use App\Repositories\RecetaRepository;
use App\Domain\Receta;
use App\Domain\Sucursal;
use App\Domain\Cadena;
use DateTime;

class RecetaService
{
    private RecetaRepository $repo;

    public function __construct(RecetaRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Listar recetas de la sucursal del empleado actual
     */
    public function obtenerRecetasEmpleado(string $idCadena,int $idSucursal,?string $estado): array
    {
        $recetasModel = $this->repo->obtenerPorSucursal($idCadena,$idSucursal,$estado);
        
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
                $suc->id_sucursal,
                $cadenaDomain,
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

    





}
