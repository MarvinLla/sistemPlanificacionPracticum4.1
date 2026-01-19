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
    Schema::create('pnd_objetivos', function (Blueprint $table) {
        $table->id();
        $table->string('eje'); // Ejemplo: Social, Económico, Seguridad
        $table->string('nombre_objetivo'); // El texto del objetivo
        $table->text('descripcion')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pnd_objetivos');
    }
};
