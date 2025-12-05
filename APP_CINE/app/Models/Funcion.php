<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;
    
    protected $table = 'funciones';
    protected $fillable = ['pelicula_id', 'sala_id', 'fecha', 'precio'];
    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class);
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}

