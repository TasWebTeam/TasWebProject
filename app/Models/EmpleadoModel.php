<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;      // porque viene de usuarios, no es AUTO_INCREMENT
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_cadena',
        'id_sucursal',
        'id_puesto',
    ];

    /**
     * Empleado pertenece a un Usuario
     * empleados.id_usuario -> usuarios.id_usuario
     */
    public function usuario()
    {
        // Aquí debe ser belongsTo, porque empleados tiene la FK
        return $this->hasOne(
            UsuarioModel::class,
            'id_usuario',
            'id_usuario'
        );
    }

    /**
     * Empleado pertenece a una Sucursal
     * empleados.id_sucursal -> sucursales.id_sucursal
     * (la parte de id_cadena la controlas en las consultas si hace falta)
     */
    public function sucursal()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursal',
            'id_sucursal'
        );
    }

    /**
     * Empleado pertenece a una Cadena
     * empleados.id_cadena -> cadenas.id_cadena
     */
    public function cadena()
    {
        return $this->belongsTo(
            CadenaModel::class,
            'id_cadena',
            'id_cadena'
        );
    }

    /**
     * Empleado pertenece a un Puesto
     */
    public function puesto()
    {
        return $this->belongsTo(
            PuestoModel::class,
            'id_puesto',
            'id_puesto'
        );
    }

    /**
     * Empleado tiene muchas líneas de surtido
     * linea_surtido.id_empleado -> empleados.id_usuario
     */
    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_empleado',   // FK en linea_surtido
            'id_usuario'     // PK en empleados
        );
    }
}