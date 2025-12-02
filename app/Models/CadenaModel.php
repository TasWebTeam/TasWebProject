<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CadenaModel extends Model
{
    protected $table = 'cadenas';

    protected $primaryKey = 'id_cadena';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = ['id_cadena', 'nombre'];

    public function sucursales()
    {
        return $this->hasMany(SucursalModel::class, 'id_cadena', 'id_cadena');
    }
}