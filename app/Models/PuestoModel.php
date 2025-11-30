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

    //belongs to
}
