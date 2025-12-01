<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaModel extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_cadenaDestino',
        'id_sucursalDestino',
        'cedula_profesional',
        'fecha_registro',
        'fecha_recoleccion',
        'estado_pedido'
    ];

    /**
     * Relación: una receta tiene muchos detalles
     */
    public function detalles()
    {
        return $this->hasMany(
            DetalleRecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    /**
     * Relación: receta pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(
            UsuarioModel::class,
            'id_usuario',
            'id_usuario'
        );
    }

    /**
     * Relación: receta pertenece a la sucursal destino
     * Solo usamos id_sucursalDestino → id_sucursal,
     * porque Eloquent NO soporta claves compuestas en belongsTo.
     */
    public function sucursalDestino()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursalDestino', // FK en recetas
            'id_sucursal'         // clave en sucursales
        );
    }

    /**
     * Relación: receta tiene un pago
     * pagos.id_receta → recetas.id_receta
     */
    public function pago()
    {
        return $this->hasOne(
            PagoModel::class,
            'id_receta',
            'id_receta'
        );
    }
}