<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('avances', function (Blueprint $table) {
        // Relacionamos con la nueva tabla de indicadores
        $table->foreignId('indicador_proyecto_id')->nullable()->constrained('indicador_proyectos')->onDelete('set null');
        $table->string('archivo_evidencia')->nullable(); // Para guardar la ruta del archivo
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avances', function (Blueprint $table) {
            //
        });
    }
};
