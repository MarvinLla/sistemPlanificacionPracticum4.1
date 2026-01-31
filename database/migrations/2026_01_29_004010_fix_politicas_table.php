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
    // Si la tabla no existe, la crea. Si existe, le añade la columna.
    if (!Schema::hasTable('politicas')) {
        Schema::create('politicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pnd_objetivo_id')->constrained('pnd_objetivos')->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    } else {
        Schema::table('politicas', function (Blueprint $table) {
            if (!Schema::hasColumn('politicas', 'pnd_objetivo_id')) {
                $table->foreignId('pnd_objetivo_id')->after('id')->constrained('pnd_objetivos')->onDelete('cascade');
            }
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
