<?php

namespace App\Repositories;

use App\Domain\Cadena;
use App\Domain\InventarioSucursal;
use App\Domain\Paciente;
use App\Domain\Receta;
use App\Models\DetalleRecetaModel;
use App\Models\InventarioModel;
use App\Models\LineaSurtidoModel;
use App\Models\RecetaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            Log::info('Actualizando inventario', [
                'cadena' => $cadena->getIdCadena(),
                'sucursal' => $idSucursal,
                'medicamento_id' => $inv->obtenerMedicamento()->getIdMedicamento(),
                'medicamento_nombre' => $inv->obtenerMedicamento()->getNombre(),
                'stock_nuevo' => $inv->obtenerStock(),
            ]);

            $inventario = InventarioModel::where('id_cadena', $cadena->getIdCadena())
                ->where('id_sucursal', $idSucursal)
                ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
                ->first();

            if (! $inventario) {
                Log::error('Inventario no encontrado para actualizar', [
                    'cadena' => $cadena->getIdCadena(),
                    'sucursal' => $idSucursal,
                    'medicamento_id' => $inv->obtenerMedicamento()->getIdMedicamento(),
                    'medicamento_nombre' => $inv->obtenerMedicamento()->getNombre(),
                ]);

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

            Log::info('Inventario actualizado exitosamente', [
                'id_inventario' => $inventario->id_inventario,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $inventario->stock_actual,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar inventario', [
                'error' => $e->getMessage(),
                'cadena' => $cadena->getIdCadena(),
                'sucursal' => $idSucursal,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function guardarReceta(Receta $receta): bool
    {
        try {
            Log::info('Guardando receta', [
                'id_receta' => $receta->getIdReceta(),
            ]);

            RecetaModel::where('id_receta', $receta->getIdReceta())
                ->update([
                    'estado_pedido' => $receta->getEstadoPedido(),
                    'fecha_recoleccion' => $receta->getFechaRecoleccion()
                        ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                        : null,
                ]);

            Log::info('Receta guardada exitosamente', [
                'id_receta' => $receta->getIdReceta(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error al guardar receta', [
                'error' => $e->getMessage(),
                'id_receta' => $receta->getIdReceta(),
            ]);

            return false;
        }
    }
public function guardarPagoReceta(int $idReceta, int $idTarjeta, float $monto)
{
    $pago = new PagoRecetaModel();
    $pago->id_receta = $idReceta;
    $pago->id_tarjeta = $idTarjeta;
    $pago->monto = $monto;
    $pago->save();

    Log::info('Pago guardado correctamente', [
        'id_receta' => $idReceta,
        'id_tarjeta' => $idTarjeta,
        'monto' => $monto,
    ]);
}

    public function guardarRecetaCompleta(Paciente $paciente, Receta $receta): RecetaModel
    {
        try {
            Log::info('Guardando receta completa', [
                'paciente_id' => $paciente->getId(),
                'total_detalles' => count($receta->getDetallesReceta()),
            ]);

            $recetaModel = new RecetaModel;
            $recetaModel->id_usuario = $paciente->getId();
            $recetaModel->id_cadenaDestino = $receta->getSucursal()->getCadena()->getIdCadena();
            $recetaModel->id_sucursalDestino = $receta->getSucursal()->getIdSucursal();
            $recetaModel->cedula_profesional = $receta->getCedulaProfesional();
            $recetaModel->fecha_registro = $receta->getFechaRegistro()
                                                    ? $receta->getFechaRegistro()->format('Y-m-d H:i:s')
                                                    : now();
            $recetaModel->fecha_recoleccion = $receta->getFechaRecoleccion()
                                                    ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                                                    : null;
            $recetaModel->estado_pedido = $receta->getEstadoPedido();

            $recetaModel->save();

            Log::info('RecetaModel guardada', [
                'id_receta' => $recetaModel->id_receta,
            ]);

            foreach ($receta->getDetallesReceta() as $detalle) {
                Log::info('Guardando detalle', [
                    'medicamento' => $detalle->getMedicamento()->getNombre(),
                    'cantidad' => $detalle->getCantidad(),
                ]);

                $detalleModel = new DetalleRecetaModel;
                $detalleModel->id_receta = $recetaModel->id_receta;
                $detalleModel->id_medicamento = $detalle->getMedicamento()->getIdMedicamento();
                $detalleModel->cantidad = $detalle->getCantidad();
                $detalleModel->precio = $detalle->getPrecio();
                $detalleModel->save();

                foreach ($detalle->getLineasSurtido() as $linea) {
                    $sucursalLinea = $linea->getSucursal();

                    Log::info('Guardando línea de surtido', [
                        'sucursal' => $sucursalLinea->getNombre(),
                        'cantidad' => $linea->getCantidad(),
                        'estado' => $linea->getEstadoEntrega(),
                    ]);

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

            Log::info('Receta completa guardada exitosamente', [
                'id_receta' => $recetaModel->id_receta,
                'total_detalles' => count($receta->getDetallesReceta()),
            ]);

            return $recetaModel;

        } catch (\Exception $e) {
            Log::error('Error al guardar receta completa', [
                'error' => $e->getMessage(),
                'paciente_id' => $paciente->getId(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function actualizarEstadoReceta(Receta $receta): bool
    {
        try {
            $recetaModel = RecetaModel::find($receta->getIdReceta());

            if (! $recetaModel) {
                Log::warning('Receta no encontrada para actualizar estado', [
                    'id_receta' => $receta->getIdReceta(),
                ]);

                return false;
            }

            $recetaModel->estado_pedido = $receta->getEstadoPedido();
            $recetaModel->save();

            Log::info('Estado de receta actualizado', [
                'id_receta' => $receta->getIdReceta(),
                'nuevo_estado' => $receta->getEstadoPedido(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de receta', [
                'error' => $e->getMessage(),
                'id_receta' => $receta->getIdReceta(),
            ]);

            return false;
        }
    }
}
