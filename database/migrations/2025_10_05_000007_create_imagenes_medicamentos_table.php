<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void{Schema::create('imagenes_medicamentos', function (Blueprint $table) {$table->id('idImagen');$table->string('URL');});}
     
  public function down(): void{Schema::dropIfExists('imagenes_medicamentos');}
};