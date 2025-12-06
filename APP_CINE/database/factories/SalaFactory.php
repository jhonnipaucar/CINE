<?php

namespace Database\Factories;

use App\Models\Sala;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaFactory extends Factory
{
    protected $model = Sala::class;

    public function definition()
    {
        return [
            'nombre' => 'Sala ' . $this->faker->numberBetween(1, 10),
            'capacidad' => $this->faker->numberBetween(40, 100),
        ];
    }
}
