<?php

namespace Database\Factories;

use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuncionFactory extends Factory
{
    protected $model = Funcion::class;

    public function definition()
    {
        $now = now();
        $fecha = $now->clone()->addDays(rand(1, 30))->setTime(rand(9, 22), rand(0, 59), 0)->setMicros(0);
        
        return [
            'pelicula_id' => Pelicula::factory(),
            'sala_id' => Sala::factory(),
            'fecha' => $fecha->toDateTimeString(),
            'precio' => $this->faker->randomFloat(2, 5, 20),
        ];
    }
}
