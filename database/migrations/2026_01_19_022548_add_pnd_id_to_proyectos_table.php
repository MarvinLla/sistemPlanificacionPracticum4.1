<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Creamos la columna y la relación
            // Usamos nullable() por si ya tienes proyectos creados, para que no den error
            $table->foreignId('pnd_objetivo_id')
                  ->nullable()
                  ->after('id') // Opcional: la pone después de la columna ID
                  ->constrained('pnd_objetivos')
                  ->onDelete('set null'); // Si borras un objetivo, el proyecto no se borra
        });
    }

    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Pasos para revertir la migración en orden inverso
            $table->dropForeign(['pnd_objetivo_id']);
            $table->dropColumn('pnd_objetivo_id');
        });
    }
};