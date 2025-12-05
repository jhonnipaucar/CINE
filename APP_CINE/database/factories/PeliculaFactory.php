<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelicula>
 */
class PeliculaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'sinopsis' => $this->faker->paragraph(),
            'duracion' => $this->faker->numberBetween(80, 180),
            'poster_url' => $this->faker->imageUrl(300, 450, 'movies', true),
        ];
    }
}
