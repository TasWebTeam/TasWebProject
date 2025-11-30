<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MedicamentoModel;

class SucursalModel extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';
    protected $fillable = ['id_sucursal', 'id_cadena', 'nombre','latitud','longitud'];
    public $timestamps = false;

    public function cadena()
    {
        return $this->belongsTo(CadenaModel::class, 'id_cadena', 'id_cadena');
    }

    public function inventarios()
    {
        return $this->hasMany(
            InventarioModel::class,
            'id_sucursal',
            'id_sucursal'
        )->whereColumn('inventarios.id_cadena', 'sucursales.id_cadena');
    }

    public function recetas(){
        //return $this->hasMany();
    }

    public function empleados(){

    }

    /*public function medications()
    {
        return $this->belongsToMany(
            MedicamentoModel::class,
            'inventarios',
            'id_sucursal',
            'id_medicamento'
        )->withPivot('stock_actual', 'stock_minimo', 'stock_maximo');
    }*/
}