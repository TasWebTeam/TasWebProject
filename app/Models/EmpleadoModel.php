<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_cadena',
        'id_sucursal',
        'id_puesto'
    ];

    public function puesto()
    {
        return $this->belongsTo(PuestoModel::class, 'id_puesto', 'id_puesto');
    }
}
