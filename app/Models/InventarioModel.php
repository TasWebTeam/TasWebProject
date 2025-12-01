<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioModel extends Model
{
    protected $table = 'inventarios';

    // PK artificial de la migración: id_inventario
    protected $primaryKey = 'id_inventario';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_inventario',
        'id_cadena',
        'id_sucursal',
        'id_medicamento',
        'stock_minimo',
        'stock_maximo',
        'stock_actual',
        'precio_actual',
    ];

    /**
     * Inventario pertenece a una sucursal
     * inventarios.id_sucursal -> sucursales.id_sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursal',
            'id'
        );
    }

    /**
     * Inventario pertenece a una cadena
     * inventarios.id_cadena -> cadenas.id_cadena
     */
    public function cadena()
    {
        return $this->belongsTo(
            CadenaModel::class,
            'id_cadena',
            'id_cadena'
        );
    }

    /**
     * Inventario pertenece a un medicamento
     */
    public function medicamento()
    {
        return $this->belongsTo(
            MedicamentoModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    /**
     * (Opcional) Helper para obtener la sucursal exacta
     * combinando id_sucursal + id_cadena, si lo necesitas en alguna parte.
     * No es relación de Eloquent, solo un método de utilidad.
     */
    public function sucursalExacta()
    {
        return SucursalModel::where('id_sucursal', $this->id_sucursal)
            ->where('id_cadena', $this->id_cadena)
            ->first();
    }
}