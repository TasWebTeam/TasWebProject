<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaSurtidoModel extends Model
{
    protected $table = 'linea_surtido';

    protected $primaryKey = 'id_linea_surtido';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'id_cadenaSurtido',
        'id_sucursalSurtido',
        'id_detalle_receta',
        'estado_entrega',
        'cantidad'
    ];
    
    public function receta()
    {
        return $this->belongsTo(
            RecetaModel::class,
            'id_receta',
            'id_receta'
        );
    }

    public function detalleReceta(){
        return $this->belongsTo(
            DetalleRecetaModel::class,
            'id_detalle_receta',
            'id_detalle_receta'
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

    public function sucursalSurtido()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursalSurtido',
            'id'
        );
    }
}