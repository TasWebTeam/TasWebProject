<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->char('id_cadena', 3);
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_puesto');

            $table->primary('id_usuario', 'pk_empleados');

            $table->foreign('id_usuario', 'fk_empleados_usuarios')
                ->references('id_usuario')
                ->on('usuarios');

            $table->foreign('id_cadena', 'fk_empleados_cadenas')
                ->references('id_cadena')
                ->on('cadenas');

            $table->foreign('id_sucursal', 'fk_empleados_sucursales')
                ->references('id')
                ->on('sucursales');

            $table->foreign('id_puesto', 'fk_empleados_puestos')
                ->references('id_puesto')
                ->on('puestos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
