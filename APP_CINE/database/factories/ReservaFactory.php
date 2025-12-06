<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Funcion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'funcion_id' => Funcion::factory(),
            'asientos' => $this->faker->numberBetween(1, 5),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'comentarios' => $this->faker->optional()->sentence(),
        ];
    }
}
