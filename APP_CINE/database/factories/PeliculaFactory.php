<?php

namespace Database\Factories;

use App\Models\Pelicula;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeliculaFactory extends Factory
{
    protected $model = Pelicula::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->words(3, true),
            'sinopsis' => $this->faker->paragraph(),
            'duracion' => $this->faker->numberBetween(90, 180),
            'poster_url' => $this->faker->imageUrl(),
            'tmdb_id' => $this->faker->randomNumber(),
        ];
    }
}
