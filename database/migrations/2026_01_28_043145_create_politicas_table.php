<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // 1. Creamos la columna solo si no existe
            if (!Schema::hasColumn('proyectos', 'pnd_objetivo_id')) {
                $table->unsignedBigInteger('pnd_objetivo_id')->nullable()->after('id');
            }

            // 2. Intentamos crear la llave foránea
            // Usamos un try-catch manual o simplemente lo definimos fuera si estamos seguros
            // Pero para mayor seguridad en Laravel, mejor definir la relación:
        });

        // Es mejor hacer la relación en un segundo bloque para asegurar que la columna ya existe
        Schema::table('proyectos', function (Blueprint $table) {
            $table->foreign('pnd_objetivo_id')
                  ->references('id')
                  ->on('pnd_objetivos')
                  ->onDelete('set null');
            
            if (!Schema::hasColumn('proyectos', 'justificacion_pnd')) {
                $table->text('justificacion_pnd')->nullable()->after('pnd_objetivo_id');
            }
        });
    }

    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Eliminamos primero la relación y luego las columnas
            $table->dropForeign(['pnd_objetivo_id']); 
            $table->dropColumn(['pnd_objetivo_id', 'justificacion_pnd']);
        });
    }
};