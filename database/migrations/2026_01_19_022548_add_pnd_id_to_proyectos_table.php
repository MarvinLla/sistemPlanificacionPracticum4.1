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
            
            $table->foreignId('pnd_objetivo_id')
                  ->nullable()
                  ->after('id') 
                  ->constrained('pnd_objetivos')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('proyectos', function (Blueprint $table) {
           
            $table->dropForeign(['pnd_objetivo_id']);
            $table->dropColumn('pnd_objetivo_id');
        });
    }
};