<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelicula_genero', function (Blueprint $table) {
            $table->unsignedBigInteger('pelicula_id');
            $table->unsignedBigInteger('genero_id');

            $table->foreign('pelicula_id')->references('id')->on('peliculas')->onDelete('cascade');
            $table->foreign('genero_id')->references('id')->on('generos')->onDelete('cascade');

            $table->primary(['pelicula_id', 'genero_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelicula_genero');
    }
};
