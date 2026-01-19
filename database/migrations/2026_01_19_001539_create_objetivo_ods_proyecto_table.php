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
    Schema::create('objetivo_ods_proyecto', function (Blueprint $table) {
        $table->id();
        // Relación con Proyectos
        $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
        // Relación con ODS (Asegúrate de que el nombre de la tabla sea 'objetivos_ods')
        $table->foreignId('objetivo_ods_id')->constrained('objetivos_ods')->onDelete('cascade');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('objetivo_ods_proyecto');
}
};
