<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaSurtidoModel extends Model
{
    protected $table = 'linea_surtido';

    // PK artificial de la migración
    protected $primaryKey = 'id_linea_surtido';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_receta',
        'id_medicamento',
        'id_cadenaSurtido',
        'id_sucursalSurtido',
        'id_empleado',
        'estado_entrega',
        'cantidad',
    ];

    /**
     * Línea de surtido pertenece a una Receta
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
     * Línea de surtido pertenece a un Medicamento
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
     * (Opcional) Helper para obtener el DetalleReceta exacto
     * por (id_receta + id_medicamento).
     * No es una relación Eloquent “pura”, es un método de utilidad.
     */
    public function detalleReceta()
    {
        return DetalleRecetaModel::where('id_receta', $this->id_receta)
            ->where('id_medicamento', $this->id_medicamento)
            ->first();
    }

    /**
     * Sucursal donde se surte la línea (por id_sucursalSurtido)
     * La parte de id_cadenaSurtido la manejas en lógica de negocio
     * o con un índice UNIQUE en la BD.
     */
    public function sucursalSurtido()
    {
        return $this->belongsTo(
            SucursalModel::class,
            'id_sucursalSurtido',
            'id_sucursal'
        );
    }

    /**
     * Empleado que surtió la línea
     * linea_surtido.id_empleado -> empleados.id_usuario
     */
    public function empleado()
    {
        return $this->belongsTo(
            EmpleadoModel::class,
            'id_empleado',   // FK en linea_surtido
            'id_usuario'     // PK en empleados
        );
    }
}