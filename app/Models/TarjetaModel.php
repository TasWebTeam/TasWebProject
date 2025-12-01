<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarjetaModel extends Model
{
    protected $table = 'tarjetas';
    protected $primaryKey = 'id_tarjeta';
    public $timestamps = false;
    protected $fillable = ['id_usuario', 'last4', 'brand', 'fecha_exp'];

    public function usuario()
    {
        return $this->hasOne(UsuarioModel::class, 'id_usuario', 'id_usuario');
    }

    public function pagos()
    {
        return $this->hasOne(PagoModel::class, 'id_tarjeta', 'id_tarjeta');
    }
}
