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
            $table->unsignedBigInteger('departamento_id')->nullable()->after('auxiliar_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'departamento_id')) {
                // Intentar eliminar foreign key si existe
                try {
                    $table->dropForeign(['departamento_id']);
                } catch (\Exception $e) {
                    // Ignorar si no existe
                }
                $table->dropColumn('departamento_id');
            }
        });
    }
};
