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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function funcion()
    {
        return $this->belongsTo(Funcion::class);
    }
}

