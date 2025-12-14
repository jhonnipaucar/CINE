<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'funciones';
    protected $fillable = ['pelicula_id', 'sala_id', 'fecha', 'hora', 'precio'];
    protected $casts = [
        'fecha' => 'datetime',
    ];
    
    // Agregar el atributo calculado a la respuesta JSON
    protected $appends = ['asientos_disponibles'];

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
        // Obtener la capacidad de la sala
        $capacidad = $this->sala?->capacidad ?? 0;
        $asientosReservados = $this->reservas()->count();
        return $capacidad - $asientosReservados;
    }
}

