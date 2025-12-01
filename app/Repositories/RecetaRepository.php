<?php

namespace App\Repositories;

use App\Models\RecetaModel;
use App\Models\SucursalModel;
use Illuminate\Support\Collection;

class RecetaRepository
{
    /**
     * Recetas de una sucursal por estado
     */



    /*public function obtenerPorSucursal(String $idCadena, int $idSucursal): Collection
    {
        return RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_cadenaDestino', $idCadena)
            ->where('id_sucursalDestino', $idSucursal)
            ->whereIn('estado_pedido', ['en_proceso', 'lista_para_recoleccion'])
            ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) <= 72")
            ->orderByDesc('fecha_registro')
            ->get();
    }*/
    public function obtenerPorSucursal(string $idCadena, int $idSucursal, ?string $estado = null)
    {
        $query = RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_cadenaDestino', $idCadena)
            ->where('id_sucursalDestino', $idSucursal)
            ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) <= 72");

        // Aplicar filtro de estado si lo envían
        if (!empty($estado)) {
            $query->where('estado_pedido', $estado);
        } else {
            // Si no manda estado, mostramos ambos
            $query->whereIn('estado_pedido', ['en_proceso', 'lista_para_recoleccion']);
        }

        return $query->orderByDesc('fecha_registro')->get();
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


    public function obtenerExpiradasPorSucursal(String $idCadena, int $idSucursal): Collection
    {
        return RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_cadenaDestino', $idCadena)
            ->where('id_sucursalDestino', $idSucursal)
            ->where(function ($q) {
            $q->where(function ($q2) {
                // Recetas que estaban listas y ya vencieron
                $q2->where('estado_pedido', 'lista_para_recoleccion')
                   ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) > 72");
            })
            // O bien, ya están en proceso de devolución
            ->orWhere('estado_pedido', 'devolviendo');
        })
        ->orderByDesc('fecha_registro')
        ->get();
    }

    /**
     * Obtener sucursal (Eloquent) por cadena y sucursal
     */
    public function obtenerSucursalPorCadenaYSucursal(String $idCadena, int $idSucursal): ?SucursalModel
    {
        return SucursalModel::with('cadena')
            ->where('id_cadena', $idCadena)
            ->where('id_sucursal', $idSucursal)
            ->first();
    }


}
