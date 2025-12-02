<?php
namespace App\Repositories;

use App\Models\CadenaModel;
use App\Models\SucursalModel;
use App\Models\InventarioModel;
use App\Models\RecetaModel;
use App\Models\UsuarioModel;
use App\Models\DetalleRecetaModel;
use App\Models\LineaSurtidoModel;

use App\Domain\Cadena;
use App\Domain\Sucursal;
use App\Domain\InventarioSucursal;
use App\Domain\Receta;
use App\Domain\Paciente;
use App\Domain\Medicamento;
use App\Domain\DetalleReceta;
use App\Domain\LineaSurtido;
use App\Domain\Pago;

use Illuminate\Support\Facades\DB;
class ActualizarRepository{
    
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
        $inventario= InventarioModel::where('id_cadena', $cadena->getIdCadena())
                 ->where('id_sucursal', $idSucursal)
                 ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
                 ->first();

        $inventario->stock_actual = $inv->obtenerStock();
        $inventario->save();
    }

    public function guardarReceta(Receta $receta): bool
    {
        try {
            RecetaModel::where('id_receta', $receta->getIdReceta())
                ->update([
                    'estado_pedido'      => $receta->getEstadoPedido(),
                    'fecha_recoleccion'  => $receta->getFechaRecoleccion()
                        ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                        : null
                ]);

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

     public function guardarRecetaCompleta(Paciente $paciente, Receta $receta): RecetaModel
    {
        // 1) Guardar/crear la receta
        $recetaModel = new RecetaModel();
        $recetaModel->id_receta = $receta->getIdReceta();
        $recetaModel->id_usuario      = $paciente->getId(); // o el método que tengas
        $recetaModel->id_cadenaDestino      = $receta->getSucursal()->getCadena()->getIdCadena();
        $recetaModel->id_sucursalDestino      = $receta->getSucursal()->getIdSucursal();
        $recetaModel->cedula_profesional = $receta->getCedulaProfesional();
        $recetaModel->fecha_registro   = $receta->getFechaRegistro()
                                            ? $receta->getFechaRegistro()->format('Y-m-d H:i:s')
                                            : now();
        $recetaModel->fecha_recoleccion = $receta->getFechaRecoleccion()
                                              ? $receta->getFechaRecoleccion()->format('Y-m-d H:i:s')
                                              : null;
        $recetaModel->estado_pedido    = $receta->getEstadoPedido();


        $recetaModel->save();

        // 2) Guardar cada DetalleReceta
        foreach ($receta->getDetallesReceta() as $detalle) {

            /** @var \App\Domain\DetalleReceta $detalle */
            $detalleModel = new DetalleRecetaModel();
            $detalleModel->id_receta       = $recetaModel->id_receta;
            $detalleModel->id_medicamento  = $detalle->getMedicamento()->getIdMedicamento();
            $detalleModel->cantidad        = $detalle->getCantidad();
            $detalleModel->precio = $detalle->getPrecio();
            $detalleModel->save();

            // 3) Guardar cada LineaSurtido de ese detalle
            foreach ($detalle->getLineasSurtido() as $linea) {
                /** @var \App\Domain\LineaSurtido $linea */

                $sucursalLinea = $linea->getSucursal();

                $lineaModel = new LineaSurtidoModel();
                $lineaModel->id_receta = $receta->getIdReceta();
                $lineaModel->id_medicamento = $detalle->getMedicamento()->getIdMedicamento();
                $lineaModel->id_cadenaSurtido       = $sucursalLinea->getCadena()->getIdCadena();
                $lineaModel->id_sucursalSurtido     = $sucursalLinea->getIdSucursal();
                $lineaModel->estado_entrega  = $linea->getEstadoEntrega();
                $lineaModel->cantidad        = $linea->getCantidad();
                $lineaModel->save();
            }
        }

        return $recetaModel;
    }

    public function actualizarEstadoReceta(Receta $receta): bool
    {
        try {
            RecetaModel::find($receta->getidReceta());
            if (!$receta) {
                return false;
            }
            //$receta->save();
                return true;
            } catch (\Exception $e) {
                return false;
            }
    }

    
}