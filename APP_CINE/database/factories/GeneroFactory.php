<?php

namespace Database\Factories;

use App\Models\Genero;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneroFactory extends Factory
{
    protected $model = Genero::class;

    public function definition()
    {
        $generos = ['Acción', 'Aventura', 'Terror', 'Comedia', 'Fantasía', 'Drama', 'Romance', 'Ciencia ficción'];

        return [
            'nombre' => $this->faker->randomElement($generos),
        ];
    }
}
