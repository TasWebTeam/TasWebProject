<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'correo', 
        'nip',
        'nombre',
        'apellido',
        'sesion_activa',
        'intentos_login',
        'ultimo_intento',
        'bloqueado_hasta',
        'rol'
    ];
    protected $hidden = ['nip'];

    public $timestamps = false;

    public function prescription()
    {
        return $this->hasMany(RecetaModel::class, 'id_receta', 'id_receta');
    }

    public function tarjeta()
    {
        return $this->hasOne(TarjetaModel::class, 'id_usuario', 'id_usuario');
    }

    public function empleado()
    {
        return $this->hasOne(EmpleadoModel::class, 'id_usuario', 'id_usuario');
    }



}