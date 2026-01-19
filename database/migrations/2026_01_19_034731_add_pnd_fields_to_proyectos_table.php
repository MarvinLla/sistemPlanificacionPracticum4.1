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
        // Añade el ID para el objetivo PND (si no lo tenías ya)
        if (!Schema::hasColumn('proyectos', 'pnd_objetivo_id')) {
            $table->unsignedBigInteger('pnd_objetivo_id')->nullable()->after('programa_id');
        }
        
        // Añade la columna de la justificación que está causando el error
        $table->text('justificacion_pnd')->nullable()->after('pnd_objetivo_id');
    });
}

public function down()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->dropColumn(['pnd_objetivo_id', 'justificacion_pnd']);
    });
}
};
