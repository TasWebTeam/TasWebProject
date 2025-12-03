<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentoModel extends Model
{
    protected $table = 'medicamentos';
    protected $primaryKey = 'id_medicamento';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'especificacion',
        'laboratorio',
        'es_controlado',
    ];

    public function detallesReceta()
    {
        return $this->hasMany(
            DetalleRecetaModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    public function imagen()
    {
        return $this->belongsTo(ImagenMedicamentoModel::class, 'idImagen', 'idImagen');
    }

    public function inventarios()
    {
        return $this->hasMany(
            InventarioModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }
}