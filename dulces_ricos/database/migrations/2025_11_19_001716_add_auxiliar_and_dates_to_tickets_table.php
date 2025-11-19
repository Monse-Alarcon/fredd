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
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('auxiliar_id')->nullable()->after('usuario_id');
            $table->timestamp('fecha_asignacion')->nullable()->after('auxiliar_id');
            $table->timestamp('fecha_finalizacion')->nullable()->after('fecha_asignacion');
            
            // Agregar foreign key para auxiliar_id
            $table->foreign('auxiliar_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['auxiliar_id']);
            $table->dropColumn(['auxiliar_id', 'fecha_asignacion', 'fecha_finalizacion']);
        });
    }
};
