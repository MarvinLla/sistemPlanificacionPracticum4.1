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
        // Agregamos programa_id si no existe
        if (!Schema::hasColumn('proyectos', 'programa_id')) {
            $table->unsignedBigInteger('programa_id')->nullable()->after('entidad_id');
        }

        // Agregamos metas_finales si no existe
        if (!Schema::hasColumn('proyectos', 'metas_finales')) {
            $table->text('metas_finales')->nullable()->after('objetivos');
        }
    });
}

public function down()
{
    Schema::table('proyectos', function (Blueprint $table) {
        $table->dropColumn(['programa_id', 'metas_finales']);
    });
}
};
