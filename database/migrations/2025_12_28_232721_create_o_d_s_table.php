<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cambiamos el nombre para que coincida con tu Modelo
        Schema::create('objetivos_ods', function (Blueprint $table) {
            $table->id();
            // Agregamos paréntesis a nullable() y usamos text para descripciones largas
            $table->text('nombreObjetivo')->nullable(); 
            $table->string('descripcion')->nullable();
            $table->text('metasAsociadas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objetivos_ods');
    }
};