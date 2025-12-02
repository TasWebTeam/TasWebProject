<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalModel extends Model
{
    protected $table = 'sucursales';

    protected $primaryKey = 'id';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'id_sucursal',
        'id_cadena',
        'nombre',
        'latitud',
        'longitud',
    ];

    public function cadena()
    {
        return $this->belongsTo(
            CadenaModel::class,
            'id_cadena',
            'id_cadena'
        );
    }

    public function inventarios()
    {
        return $this->hasMany(
            InventarioModel::class,
            'id_sucursal',   
            'id'    
        );
    }

    public function recetasDestino()
    {
        return $this->hasMany(
            RecetaModel::class,
            'id_sucursalDestino',
            'id'
        );
    }

    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_sucursalSurtido',
            'id'
        );
    }

    public function empleados()
    {
        return $this->hasMany(
            EmpleadoModel::class,
            'id_sucursal',
            'id'
        );
    }

    public function scopePorClaveLogica($query, string $idCadena, int $idSucursal)
    {
        return $query->where('id_cadena', $idCadena)
                     ->where('id_sucursal', $idSucursal);
    }
}