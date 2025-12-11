<?php

namespace Database\Factories;

use App\Models\Pelicula;
use App\Models\Sala;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Funcion>
 */
class FuncionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pelicula_id' => Pelicula::factory(),
            'sala_id' => Sala::factory(),
            'fecha' => Carbon::now()->addDays($this->faker->numberBetween(1, 30))->format('Y-m-d H:i:s'),
            'precio' => $this->faker->randomFloat(2, 8, 20),
        ];
    }
}
