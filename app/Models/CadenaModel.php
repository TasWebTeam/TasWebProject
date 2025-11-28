<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CadenaModel extends Model
{
    protected $table = 'cadenas';
    protected $fillable = ['id_cadena', 'nombre'];
    public $timestamps = false;
}
