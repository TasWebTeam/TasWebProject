<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalModel extends Model
{
    protected $table = 'sucursales';

    // PK real de la tabla (columna "id")
    protected $primaryKey = 'id';
    public $incrementing = true;

    public $timestamps = false;

    // Campos asignables (incluye la clave lógica)
    protected $fillable = [
        'id',
        'id_sucursal',
        'id_cadena',
        'nombre',
        'latitud',
        'longitud',
    ];

    /**
     * Sucursal pertenece a una cadena
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
     * Inventarios de esta sucursal
     * inventarios.id_sucursal -> sucursales.id_sucursal
     */
    public function inventarios()
    {
        return $this->hasMany(
            InventarioModel::class,
            'id_sucursal',   // FK en inventarios
            'id'    // clave lógica en sucursales
        );
    }

    /**
     * Recetas cuyo destino es esta sucursal
     * recetas.id_sucursalDestino -> sucursales.id_sucursal
     */
    public function recetasDestino()
    {
        return $this->hasMany(
            RecetaModel::class,
            'id_sucursalDestino',
            'id_sucursal'
        );
    }

    /**
     * Líneas de surtido realizadas en esta sucursal
     * linea_surtido.id_sucursalSurtido -> sucursales.id_sucursal
     */
    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_sucursalSurtido',
            'id_sucursal'
        );
    }

    /**
     * Empleados asignados a esta sucursal
     * empleados.id_sucursal -> sucursales.id_sucursal
     */
    public function empleados()
    {
        return $this->hasMany(
            EmpleadoModel::class,
            'id_sucursal',
            'id_sucursal'
        );
    }

    // (Opcional) helper para búsquedas por la clave lógica
    public function scopePorClaveLogica($query, string $idCadena, int $idSucursal)
    {
        return $query->where('id_cadena', $idCadena)
                     ->where('id_sucursal', $idSucursal);
    }
}