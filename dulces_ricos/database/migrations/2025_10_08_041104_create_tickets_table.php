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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descripcion');
        $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
        $table->enum('estado', ['abierto', 'en progreso', 'cerrado'])->default('abierto');
        $table->unsignedBigInteger('usuario_id')->nullable(); // Relación con usuario
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
