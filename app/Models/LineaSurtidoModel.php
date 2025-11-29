<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaSurtidoModel extends Model
{
    protected $table = 'linea_surtido';
    public $timestamps = false;
    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'id_cadenaSurtido',
        'id_sucursalSurtido',
        'id_empleado',
        'estado_entrega',
        'cantidad'
    ];
}