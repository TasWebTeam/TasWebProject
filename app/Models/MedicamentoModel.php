<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentoModel extends Model
{
    protected $table = 'medicamentos';
    protected $primaryKey = 'id_medicamento';
    protected $fillable = ['id_medicamento', 'nombre', 'especificacion', 'laboratorio','es_controlado'];
    public $timestamps =  false;

    public function prescriptions()
    {
        return $this->hasMany(DetalleRecetaModel::class, 'id_medicamento', 'id_medicamento');
    }
}