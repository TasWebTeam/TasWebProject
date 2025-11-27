<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRecetaModel extends Model
{
    protected $table = 'detalle_receta';
    protected $fillable = ['id_receta', 'id_medicamento', 'cantidad','precio'];
    public $timestamps = false;

    public function prescription()
    {
        return $this->belongsTo(RecetaModel::class, 'id_receta', 'id_receta');
    }

    public function medication()
    {
        return $this->belongsTo(MedicamentoModel::class, 'id_medicamento', 'id_medicamento');
    }
}
