<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoModel extends Model
{
    protected $table = 'pagos';

    // La PK es id_receta según tu migración
    protected $primaryKey = 'id_receta';
    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'id_receta',
        'id_tarjeta',
        'monto'
    ];

    /**
     * Pago pertenece a una receta.
     * pagos.id_receta -> recetas.id_receta
     */
    public function receta()
    {
        return $this->hasOne(
            RecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    /**
     * Pago pertenece a una tarjeta.
     * pagos.id_tarjeta -> tarjetas.id_tarjeta
     */
    public function tarjeta()
    {
        return $this->hasOne(
            TarjetaModel::class,
            'id_tarjeta',
            'id_tarjeta'
        );
    }
}