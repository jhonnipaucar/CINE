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
            // Eliminar columnas TMDB si existen
            $columns = Schema::getColumnListing('peliculas');
            
            if (in_array('tmdb_id', $columns)) {
                $table->dropColumn('tmdb_id');
            }
            if (in_array('calificacion_tmdb', $columns)) {
                $table->dropColumn('calificacion_tmdb');
            }
            if (in_array('votos_tmdb', $columns)) {
                $table->dropColumn('votos_tmdb');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->integer('tmdb_id')->nullable();
            $table->decimal('calificacion_tmdb', 3, 1)->nullable();
            $table->integer('votos_tmdb')->nullable();
        });
    }
};
