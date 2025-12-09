<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'sinopsis',
        'descripcion',
        'duracion',
        'poster_url',
        'url_imagen',
        'tmdb_id',
        'calificacion_tmdb',
        'votos_tmdb'
    ];

    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'pelicula_genero');
    }

    public function funciones()
    {
        return $this->hasMany(Funcion::class);
    }
}

