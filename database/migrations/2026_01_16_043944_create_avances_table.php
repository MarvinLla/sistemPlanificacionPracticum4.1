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
    Schema::create('avances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
        $table->string('titulo');
        $table->text('descripcion');
        $table->decimal('monto_gastado', 15, 2);
        $table->string('archivo')->nullable(); // Para PDF/Docs
        $table->string('foto')->nullable();    // Para imágenes
        $table->date('fecha_avance');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avances');
    }
};
