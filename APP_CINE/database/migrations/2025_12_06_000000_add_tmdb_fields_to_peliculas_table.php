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
        Schema::table('peliculas', function (Blueprint $table) {
            // Agregar descripción si no existe
            if (!Schema::hasColumn('peliculas', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('sinopsis');
            }
            
            // Agregar campos TMDb
            if (!Schema::hasColumn('peliculas', 'tmdb_id')) {
                $table->integer('tmdb_id')->nullable()->unique()->after('id');
            }
            
            if (!Schema::hasColumn('peliculas', 'calificacion_tmdb')) {
                $table->decimal('calificacion_tmdb', 3, 1)->nullable()->after('duracion');
            }
            
            if (!Schema::hasColumn('peliculas', 'votos_tmdb')) {
                $table->integer('votos_tmdb')->nullable()->after('calificacion_tmdb');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->dropColumnIfExists('descripcion');
            $table->dropColumnIfExists('tmdb_id');
            $table->dropColumnIfExists('calificacion_tmdb');
            $table->dropColumnIfExists('votos_tmdb');
        });
    }
};
