<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MedicationModel;
;


class ImagenMedicamentoModel extends Model
{
    protected $table = 'imagenes_medicamentos';
    protected $primaryKey = 'idImagen';

    protected $fillable = [
        'URL'
    ];
    public function medicamentos()
    {
        return $this->hasMany(MedicationModel::class, 'idImagen', 'idImagen');
    }
}
