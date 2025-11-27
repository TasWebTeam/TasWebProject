<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linea_surtido', function (Blueprint $table) {
            $table->unsignedBigInteger('id_receta');
            $table->unsignedBigInteger('id_medicamento');
            $table->char('id_cadenaSurtido',3);
            $table->unsignedBigInteger('id_sucursalSurtido');
            $table->unsignedBigInteger('id_empleado');
            $table->string('estado_entrega', 50);
            $table->integer('cantidad');

            $table->primary(['id_receta', 'id_medicamento', 'id_cadenaSurtido', 'id_sucursalSurtido', 'id_empleado']);

            $table->foreign('id_receta')->references('id_receta')->on('recetas');
            $table->foreign('id_medicamento')->references('id_medicamento')->on('medicamentos');
            $table->foreign(['id_cadenaSurtido','id_sucursalSurtido'])->references(['id_cadena','id_sucursal'])->on('sucursales');
            //$table->foreign('id_sucursalSurtido')->references('id_sucursal')->on('sucursales');
            $table->foreign('id_empleado')->references('id_usuario')->on('empleados');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linea_surtido');
    }
};
