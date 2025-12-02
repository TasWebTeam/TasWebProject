<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleRecetaModel extends Model
{
    // Nombre correcto de la tabla
    protected $table = 'detalle_recetas';

    // PK artificial creada en la migración
    protected $primaryKey = 'id_detalle_receta';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'cantidad',
        'precio'
    ];

    public function receta()
    {
        return $this->belongsTo(
            RecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    public function medicamento()
    {
        return $this->belongsTo(
            MedicamentoModel::class,
            'id_medicamento',
            'id_medicamento'
        );
    }

    public function lineasSurtido()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_detalle_receta',
            'id_detalle_receta'
        );
    }
/*
    public function lineaPorMedicamento()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_detalle_receta',
            'id_detalle_receta'
        )->whereColumn(
            'linea_surtido.id_medicamento',
            'detalle_recetas.id_medicamento'
        );
    }*/
    
    public function lineaPorMedicamento()
    {
        return $this->hasMany(
            LineaSurtidoModel::class,
            'id_detalle_receta',
            'id_detalle_receta'
        )->where('id_medicamento', $this->id_medicamento);
    }
}