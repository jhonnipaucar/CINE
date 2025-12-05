<?php

namespace Database\Factories;

use App\Models\Funcion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'funcion_id' => Funcion::factory(),
            'user_id' => User::factory(),
            'asientos' => $this->faker->numberBetween(1, 6),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'comentarios' => $this->faker->optional()->sentence(),
        ];
    }
}
