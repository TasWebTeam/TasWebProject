<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sucursal');
            $table->char('id_cadena', 3);
            $table->string('nombre', 50);
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();

            $table->primary(['id_cadena', 'id_sucursal'], 'pk_sucursales');

            $table->foreign('id_cadena', 'fk_sucursales_cadenas')
                ->references('id_cadena')
                ->on('cadenas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
