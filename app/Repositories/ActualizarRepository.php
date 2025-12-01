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
        //dd("NAMBRE");
        DB::rollBack();
    }

    // public function actualizarInventario(Cadena $cadena, $idSucursal, InventarioSucursal $inv)
    // {
    //     // dd($cadena, $idSucursal, $inv->obtenerMedicamento()->getIdMedicamento(), $inv->obtenerStock());
    //     try {
    //         // $InventarioModel = new InventarioModel();
    //         // $InventarioModel->id_cadena   = $sucursalDom->getCadena()->getIdCadena();
    //         // $InventarioModel->id_cadena   = $sucursalDom->getCadena()->getIdCadena();
    //         // $InventarioModel->id_sucursal = $sucursalDom->getIdSucursal();
    //         // $InventarioModel->id_medicamento     = $sucursalDom->getNombre();
    //         // $InventarioModel->stock_minimo     = $sucursalDom->getLatitud();
    //         // $InventarioModel->stock_maximo   = $sucursalDom->getLongitud();
    //         // $InventarioModel->stock_actual   = $sucursalDom->getLongitud();
    //         // $InventarioModel->precio_actual  = $sucursalDom->getLongitud();

    //         // $modelo->save();
            
    //     //     'id_inventario',
    //     // 'id_cadena',
    //     // 'id_sucursal',
    //     // 'id_medicamento',
    //     // 'stock_minimo',
    //     // 'stock_maximo',
    //     // 'stock_actual',
    //     // 'precio_actual',
    //         // InventarioModel::save($inv);
    //         InventarioModel::where('id_cadena', $cadena->getIdCadena())
    //             ->where('id_sucursal', $idSucursal)
    //             ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
    //             ->update(['stock_actual' => $inv->obtenerStock()]);
    //         return true;

            
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }

    public function actualizarCadenaGDL(): void
{
    $this->beginTransaction();
    CadenaModel::where('id_cadena', 'GDL')
        ->update(['nombre' => 'Farmacias Guadalajara 2']) > 0;
    $this->commitTransaction();
}
    public function actualizarInventario(Cadena $cadena, $idSucursal, InventarioSucursal $inv): void
    {  
        $this->actualizarCadenaGDL();
        dd("holaaaaa");

        
        $inventario= InventarioModel::where('id_cadena', $cadena->getIdCadena())
                 ->where('id_sucursal', $idSucursal)
                 ->where('id_medicamento', $inv->obtenerMedicamento()->getIdMedicamento())
                 ->lockForUpdate()
                 ->first();

        
        // 🔍 Ver qué valor trae actualmente de la BD
        $original = $inventario->stock_actual;

        // 🔍 Nuevo valor desde el dominio
        $nuevo = $inv->obtenerStock();

        $inventario->stock_actual = $nuevo;

        // 🔍 Qué cree Laravel que cambió
         //dd(['dirty' => $inventario->getDirty(), 'original' => $original, 'nuevo' => $nuevo]);

        // ⚠️ Usa update() para asegurar que SOLO se actualiza stock_actual
        $inventario->update([
            'stock_actual' => $nuevo,
        ]);

        // 🔍 Confirmar en memoria
         dd(['después_update' => $inventario->stock_actual]);
 // 👈 Aquí sí usas save()
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