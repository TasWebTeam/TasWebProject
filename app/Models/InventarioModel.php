<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioModel extends Model
{
    protected $table = 'inventarios';

    protected $primaryKey = 'id_inventario';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_cadena',
        'id_sucursal',
        'id_medicamento',
        'stock_minimo',
        'stock_maximo',
        'stock_actual',
        'precio_actual',
    ];

    public function sucursal()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursal',
            'id'
        );
    }

    public function cadena()
    {
        return $this->belongsTo(
            CadenaModel::class,
            'id_cadena',
            'id_cadena'
        );
    }

    public function medicamento()
    {
        return $this->belongsTo(
            MedicamentoModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    public function sucursalExacta()
    {
        return SucursalModel::where('id_sucursal', $this->id_sucursal)
            ->where('id_cadena', $this->id_cadena)
            ->first();
    }
}