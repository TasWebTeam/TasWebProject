<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ImagenMedicamentoModel   ;

class MedicationModel extends Model
{
    protected $table = 'medicamentos';
    protected $fillable = ['id_medicamento', 'nombre', 'especificacion', 'laboratorio','es_controlado'];
    public $timestamps = false;

    public function prescriptions()
    {
        return $this->hasMany(PrescriptionMedicationModel::class, 'id_medicamento', 'id_medicamento');
    }
        public function imagen()
    {
        return $this->belongsTo(ImagenMedicamentoModel::class, 'idImagen', 'idImagen');
    }
}
