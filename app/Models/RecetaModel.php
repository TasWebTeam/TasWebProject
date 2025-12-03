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

    public function detalles()
    {
        return $this->hasMany(
            DetalleRecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            UsuarioModel::class,
            'id_usuario',
            'id_usuario'
        );
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursalDestino', 
            'id'         
        );
    }

    public function pago()
    {
        return $this->hasOne(
            PagoModel::class,
            'id_receta',
            'id_receta'
        );
    }
}