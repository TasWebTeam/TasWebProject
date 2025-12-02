<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_recetas', function (Blueprint $table) {

            $table->id('id_detalle_receta');

            $table->unsignedBigInteger('id_receta');
            $table->unsignedBigInteger('id_medicamento');

            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);

            $table->unique(['id_receta', 'id_medicamento'],'uq_detalle_receta');

            $table->foreign('id_receta')
                ->references('id_receta')
                ->on('recetas');

            $table->foreign('id_medicamento')
                ->references('id_medicamento')
                ->on('medicamentos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_recetas');
    }
};
