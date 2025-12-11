<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'funcion_id', 'numero_asiento', 'asientos', 'precio', 'estado', 'comentarios'];

    protected $casts = [
        'asientos' => 'json',
    ];

    // Estados válidos de una reserva
    const ESTADOS = [
        'pendiente' => 'Pendiente de aprobación',
        'confirmada' => 'Confirmada',
        'rechazada' => 'Rechazada',
        'cancelada' => 'Cancelada',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function funcion()
    {
        return $this->belongsTo(Funcion::class);
    }

    /**
     * Obtener el nombre del estado
     */
    public function getEstadoNombreAttribute()
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }
}

