<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programas', function (Blueprint $table) {
            $table->id();
            // Relación con el Objetivo PND (El eslabón superior)
            $table->foreignId('pnd_objetivo_id')
                  ->nullable()
                  ->constrained('pnd_objetivos')
                  ->onDelete('set null');

            $table->string('nombrePrograma')->nullable();
            $table->string('tipoPrograma')->nullable();
            $table->string('version')->nullable();
            $table->string('responsablePrograma')->nullable();  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programas');
    }
};