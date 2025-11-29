<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioModel extends Model
{
    protected $table = 'inventarios';
    protected $fillable = ['id_cadena', 'id_sucursal', 'id_medicamento', 'stock_minimo', 'stock_maximo', 'stock_actual','precio_actual'];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = null;

    public function medicamento(){
        return $this->belongsTo(MedicamentoModel::class, 'id_medicamento', 'id_medicamento');
    }

    public function sucursal(){
        return $this->belongsTo(SucursalModel::class, 'id_sucursal');
    }

    public function cadena(){
        return $this->belongsTo(CadenaModel::class, 'id_cadena');
    }
}