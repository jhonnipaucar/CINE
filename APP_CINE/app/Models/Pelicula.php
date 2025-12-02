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
        'duracion',
        'poster_url',
        'tmdb_id'
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

