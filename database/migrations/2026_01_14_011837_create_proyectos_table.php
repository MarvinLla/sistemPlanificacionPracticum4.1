<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            // Relación con Entidades
            $table->foreignId('entidad_id')->constrained('entidades')->onDelete('cascade');
            
            // Objetivos
            $table->text('objetivos');
            $table->text('objetivos_ods');
            
            // Detalles del Plan
            $table->decimal('presupuesto_estimado', 15, 2);
            $table->string('responsable');
            $table->text('beneficio');
            
            // Fechas y Contacto
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->string('correo_contacto');
            $table->string('telefono_contacto');
            
            // ESTADO DEL PROYECTO
            $table->string('estado')->default('en revisión'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};