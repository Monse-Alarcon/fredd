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
            // Verificar si la tabla departamentos existe antes de agregar la foreign key
            if (Schema::hasTable('departamentos')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('departamento_id')->nullable()->after('role');
                    $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('set null');
                });
            } else {
                // Si no existe, solo agregar la columna sin foreign key
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('departamento_id')->nullable()->after('role');
                });
            }
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'departamento_id')) {
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
