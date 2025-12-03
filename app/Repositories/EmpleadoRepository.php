<?php

namespace App\Repositories;

use App\Models\RecetaModel;
use App\Models\SucursalModel;
use Illuminate\Support\Collection;

class EmpleadoRepository
{
    public function obtenerPorSucursal(string $idCadena, int $idSucursal, ?string $estado = null)
    {
        $query = RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_cadenaDestino', $idCadena)
            ->where('id_sucursalDestino', $idSucursal)
            ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) <= 72");

        if (!empty($estado)) {
            $query->where('estado_pedido', $estado);
        } else {
            $query->whereIn('estado_pedido', ['en_proceso', 'lista_para_recoleccion']);
        }

        return $query->orderByDesc('fecha_registro')->get();
    }


    public function obtenerPorId(int $id): ?RecetaModel
    {
        return RecetaModel::with(['usuario', 'sucursalDestino', 'medications'])
            ->find($id);
    }

    public function actualizarEstado(int $idReceta, string $nuevoEstado): bool
    {
        $receta = RecetaModel::find($idReceta);

        if (!$receta) return false;

        $receta->estado_pedido = $nuevoEstado;
        return $receta->save();
    }

    public function obtenerExpiradasPorSucursal(String $idCadena, int $idSucursal): Collection
    {
        return RecetaModel::with(['usuario', 'sucursalDestino.cadena'])
            ->where('id_cadenaDestino', $idCadena)
            ->where('id_sucursalDestino', $idSucursal)
            ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->where('estado_pedido', 'lista_para_recoleccion')
                   ->whereRaw("TIMESTAMPDIFF(HOUR, fecha_recoleccion, NOW()) > 72");
            })
            ->orWhere('estado_pedido', 'devolviendo');
        })
        ->orderByDesc('fecha_registro')
        ->get();
    }

    public function obtenerSucursalPorCadenaYSucursal(String $idCadena, int $idSucursal): ?SucursalModel
    {
        return SucursalModel::with('cadena')
            ->where('id_cadena', $idCadena)
            ->where('id_sucursal', $idSucursal)
            ->first();
    }


}
