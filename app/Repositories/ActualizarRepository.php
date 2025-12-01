<?php
namespace App\Repositories;

use App\Models\CadenaModel;
use App\Models\SucursalModel;
use App\Models\InventarioModel;
use App\Models\RecetaModel;
use App\Models\UsuarioModel;

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

    public function actualizarInventario($cadena, $idSucursal, $inv)
    {
       try {
            InventarioModel::where('id_cadena', $cadena->getIdCadena())
                ->where('id_sucursal', $idSucursal)
                ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
                ->update(['stock_actual' => $inv->obtenerStock()]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
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

    
}