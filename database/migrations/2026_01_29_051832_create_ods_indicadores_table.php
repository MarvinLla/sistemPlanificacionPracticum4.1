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
    Schema::create('ods_indicadores', function (Blueprint $table) {
        $table->id();
        // Conexión con la tabla de Metas
        $table->foreignId('meta_id')->constrained('ods_metas')->onDelete('cascade');
        $table->string('nombre');
        $table->string('unidad_medida')->nullable(); // Ej: Porcentaje, Kilómetros, etc.
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ods_indicadores');
    }
};
