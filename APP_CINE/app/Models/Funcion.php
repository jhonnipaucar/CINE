<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;
    
    protected $table = 'funciones';
    protected $fillable = ['pelicula_id', 'sala_id', 'fecha', 'hora', 'precio'];
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

    // Accesores
    public function getAsientosDisponiblesAttribute()
    {
        // Total de asientos en la sala (8 filas x 12 columnas = 96)
        $totalAsientos = 96;
        $asientosReservados = $this->reservas()->count();
        return $totalAsientos - $asientosReservados;
    }
}

