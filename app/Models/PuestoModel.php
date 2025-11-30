<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuestoModel extends Model
{
    protected $table = 'puestos';
    protected $primaryKey = 'id_puesto';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function usuario()
    {
        return $this->hasMany(EmpleadoModel::class, 'id_puesto', 'id_puesto');
    }

}
