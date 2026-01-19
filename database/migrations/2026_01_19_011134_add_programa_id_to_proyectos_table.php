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
    Schema::table('proyectos', function (Blueprint $table) {
        // Añadimos la columna como llave foránea
        $table->unsignedBigInteger('programa_id')->nullable()->after('entidad_id');
        
        // Opcional: Definir la relación oficial
        $table->foreign('programa_id')->references('id')->on('programas')->onDelete('set null');
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
