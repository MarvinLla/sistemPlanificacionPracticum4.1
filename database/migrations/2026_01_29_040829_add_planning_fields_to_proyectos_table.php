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
        Schema::table('proyectos', function (Blueprint $table) {
    // 1. Indicadores (KPIs)
    $table->string('indicador_nombre')->nullable();
    $table->decimal('indicador_linea_base', 8, 2)->nullable();
    $table->decimal('indicador_meta', 8, 2)->nullable();
    $table->enum('indicador_frecuencia', ['Mensual', 'Trimestral', 'Semestral', 'Anual'])->nullable();

    // 2. Territorialización
    $table->string('ubicacion_provincia')->nullable();
    $table->string('ubicacion_canton')->nullable();
    $table->string('ubicacion_parroquia')->nullable();

    // 3. Beneficiarios
    $table->integer('beneficiarios_directos')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            //
        });
    }
};
