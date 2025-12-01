<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicamentoModel extends Model
{
    protected $table = 'medicamentos';
    protected $primaryKey = 'id_medicamento';
    public $timestamps = false;

    // No incluyo id_medicamento porque es AUTO_INCREMENT
    protected $fillable = [
        'nombre',
        'especificacion',
        'laboratorio',
        'es_controlado',
    ];

    /**
     * Detalles de receta donde participa este medicamento
     * (relación 1 medicamento -> muchos detalles)
     */
    public function detallesReceta()
    {
        return $this->hasMany(
            DetalleRecetaModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    /**
     * Inventarios en los que aparece este medicamento
     */
    public function inventarios()
    {
        return $this->hasMany(
            InventarioModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }
}