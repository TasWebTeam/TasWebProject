<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRecetaModel extends Model
{
    // Nombre correcto de la tabla
    protected $table = 'detalle_recetas';

    // PK artificial creada en la migración
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;

    public $timestamps = false;

    // Columnas que pueden asignarse en masa
    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'cantidad',
        'precio',
    ];

    /**
     * Relación con la receta a la que pertenece este detalle.
     */
    public function receta()
    {
        return $this->belongsTo(
            RecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    /**
     * Relación con el medicamento del detalle.
     */
    public function medicamento()
    {
        return $this->belongsTo(
            MedicamentoModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    /**
     * Relación general: devuelve TODAS las líneas de surtido
     * asociadas a esta receta (sin filtrar por medicamento).
     * 
     * ✔ Funciona bien con eager loading
     */
    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_receta',
            'id_receta'
        );
    }

    /**
     * Relación exacta: línea(s) de surtido correspondientes
     * específicamente a ESTE medicamento dentro de la receta.
     *
     * ✔ Compatible con eager loading
     * ✔ Usa whereColumn (la forma correcta para relaciones compuestas)
     */
    public function lineaPorMedicamento()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_receta',
            'id_receta'
        )->whereColumn(
            'linea_surtido.id_medicamento',
            'detalle_recetas.id_medicamento'
        );
    }
}
