<?php

namespace App\Repositories;

use App\Models\RecetaModel;
use Illuminate\Support\Collection;

class RecetaRepository
{
    /**
     * Recetas de una sucursal por estado
     */

    public function obtenerPorSucursal(int $idSucursal)
    {
        return RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_sucursalDestino', $idSucursal)

            // solo recetas en progreso o listas
            ->whereIn('estado_pedido', ['en_proceso', 'lista_para_recoleccion'])

            // que NO hayan pasado más de 72 horas desde fecha_registro
            ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) <= 72")

            ->orderByDesc('fecha_registro')
            ->get();
    }


    /**
     * Obtener una receta específica
     */
    public function obtenerPorId(int $id): ?RecetaModel
    {
        return RecetaModel::with(['usuario', 'sucursalDestino', 'medications'])
            ->find($id);
    }

    /**
     * Actualizar estado de receta
     */
    public function actualizarEstado(int $idReceta, string $nuevoEstado): bool
    {
        $receta = RecetaModel::find($idReceta);

        if (!$receta) return false;

        $receta->estado_pedido = $nuevoEstado;
        return $receta->save();
    }

        /**
     * Recetas expiradas de una sucursal
     */

    public function obtenerExpiradasPorSucursal(int $idSucursal)
    {
    return RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
        ->where('id_sucursalDestino', $idSucursal)
        ->whereIn('estado_pedido', ['lista_para_recoleccion'])
        ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) > 72")

        ->orderByDesc('fecha_registro')
        ->get();
    }


}
