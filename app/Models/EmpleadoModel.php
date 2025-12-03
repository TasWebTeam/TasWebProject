<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;      
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_cadena',
        'id_sucursal',
        'id_puesto',
    ];

    public function usuario()
    {
        return $this->hasOne(
            UsuarioModel::class,
            'id_usuario',
            'id_usuario'
        );
    }

    public function sucursal()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursal',
            'id_sucursal'
        );
    }

    public function cadena()
    {
        return $this->belongsTo(
            CadenaModel::class,
            'id_cadena',
            'id_cadena'
        );
    }

    public function puesto()
    {
        return $this->belongsTo(
            PuestoModel::class,
            'id_puesto',
            'id_puesto'
        );
    }

    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_empleado',   
            'id_usuario'     
        );
    }
}