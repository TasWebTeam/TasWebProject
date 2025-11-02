<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $fillable = [
        'correo', 
        'nip',
        'nombre',
        'apellido',
        'sesion_activa',
        'intentos_login',
        'ultimo_intento',
        'bloqueado_hasta'
    ];
    protected $hidden = ['nip'];

    public $timestamps = false;
}