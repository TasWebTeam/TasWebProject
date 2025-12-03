<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoModel extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_receta';
    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'id_receta',
        'id_tarjeta',
        'monto'
    ];

    public function receta()
    {
        return $this->hasOne(
            RecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    public function tarjeta()
    {
        return $this->hasOne(
            TarjetaModel::class,
            'id_tarjeta',
            'id_tarjeta'
        );
    }
}