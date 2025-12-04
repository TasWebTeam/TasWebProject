<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id('id_receta');
            $table->unsignedBigInteger('id_usuario');
            $table->char('id_cadenaDestino', 3);
            $table->unsignedBigInteger('id_sucursalDestino');
            $table->string('cedula_profesional', 20);
            $table->dateTime('fecha_registro')->default(now());
            $table->dateTime('fecha_recoleccion')->nullable();
            $table->enum('estado_pedido', [
                'en_proceso',
                'lista_para_recoleccion',
                'entregada',
                'no_recogida',
                'devolviendo'
            ])->default('en_proceso');
            
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios');
            
            $table->foreign('id_cadenaDestino')
                ->references('id_cadena')
                ->on('sucursales');

            $table->foreign('id_sucursalDestino')
                ->references('id')
                ->on('sucursales');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
