<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecetaModel extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    protected $fillable = [
        'id_usuario',
        'id_cadenaDestino',
        'id_sucursalDestino',
        'cedula_profesional',
        'fecha_registro',
        'fecha_recoleccion',
        'estado_pedido'
    ];
    public $timestamps = false;

    public function medications()
    {
        return $this->hasMany(DetalleRecetaModel::class, 'id_receta', 'id_receta');
    }
}
