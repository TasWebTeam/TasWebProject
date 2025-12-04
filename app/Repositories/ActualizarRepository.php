<?php

namespace App\Repositories;

use App\Domain\Cadena;
use App\Domain\InventarioSucursal;
use App\Domain\Paciente;
use App\Domain\Receta;
use App\Domain\Pago;
use App\Models\DetalleRecetaModel;
use App\Models\InventarioModel;
use App\Models\LineaSurtidoModel;
use App\Models\RecetaModel;
use Illuminate\Support\Facades\DB;

class ActualizarRepository
{
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollbackTransaction()
    {
        DB::rollBack();
    }

    public function actualizarInventario(Cadena $cadena, $idSucursal, InventarioSucursal $inv): void
    {
        try {
            $inventario = InventarioModel::where('id_cadena', $cadena->getIdCadena())
                ->where('id_sucursal', $idSucursal)
                ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
                ->first();

            if (! $inventario) {

                throw new \Exception(
                    'No se encontró el inventario para actualizar. '.
                    "Cadena: {$cadena->getIdCadena()}, ".
                    "Sucursal: {$idSucursal}, ".
                    "Medicamento: {$inv->obtenerMedicamento()->getNombre()}"
                );
            }

            $stockAnterior = $inventario->stock_actual;
            $inventario->stock_actual = $inv->obtenerStock();
            $inventario->save();

        } catch (\Exception $e) {

        }
    }

    public function guardarReceta(Receta $receta): bool
    {
        try {
            RecetaModel::where('id_receta', $receta->getIdReceta())
                ->update([
                    'estado_pedido' => $receta->getEstadoPedido(),
                    'fecha_recoleccion' => $receta->getFechaRecoleccion()
                        ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                        : null,
                ]);

            return true;

        } catch (\Exception $e) {

            return false;
        }
    }

    public function guardarRecetaCompleta(Paciente $paciente, Receta $receta): RecetaModel
    {
        try {
            $recetaModel = new RecetaModel;
            $recetaModel->id_usuario = $paciente->getId();
            $recetaModel->id_cadenaDestino = $receta->getSucursal()->getCadena()->getIdCadena();
            $recetaModel->id_sucursalDestino = $receta->getSucursal()->getId();
            $recetaModel->cedula_profesional = $receta->getCedulaProfesional();
            $recetaModel->fecha_registro = $receta->getFechaRegistro()
                                                    ? $receta->getFechaRegistro()->format('Y-m-d H:i:s')
                                                    : now();
            $recetaModel->fecha_recoleccion = $receta->getFechaRecoleccion()
                                                    ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                                                    : null;
            $recetaModel->estado_pedido = $receta->getEstadoPedido();

            $recetaModel->save();

            foreach ($receta->getDetallesReceta() as $detalle) {

                $detalleModel = new DetalleRecetaModel;
                $detalleModel->id_receta = $recetaModel->id_receta;
                $detalleModel->id_medicamento = $detalle->getMedicamento()->getIdMedicamento();
                $detalleModel->cantidad = $detalle->getCantidad();
                $detalleModel->precio = $detalle->getPrecio();
                $detalleModel->save();

                foreach ($detalle->getLineasSurtido() as $linea) {
                    $sucursalLinea = $linea->getSucursal();

                    $lineaModel = new LineaSurtidoModel;
                    $lineaModel->id_receta = $recetaModel->id_receta;
                    $lineaModel->id_medicamento = $detalle->getMedicamento()->getIdMedicamento();
                    $lineaModel->id_cadenaSurtido = $sucursalLinea->getCadena()->getIdCadena();
                    $lineaModel->id_sucursalSurtido = $sucursalLinea->getId();
                    $lineaModel->id_detalle_receta = $detalleModel->id_detalle_receta;
                    $lineaModel->estado_entrega = $linea->getEstadoEntrega();
                    $lineaModel->cantidad = $linea->getCantidad();
                    $lineaModel->save();
                }
            }

            return $recetaModel;

        } catch (\Exception $e) {
            
            throw $e;
        }
    }

    public function actualizarEstadoReceta(Receta $receta): bool
    {
        try {
            $recetaModel = RecetaModel::find($receta->getIdReceta());

            if (! $recetaModel) {
                return false;
            }

            $recetaModel->estado_pedido = $receta->getEstadoPedido();
            $recetaModel->save();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }
}
