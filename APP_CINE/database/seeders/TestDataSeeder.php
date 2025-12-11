<?php

namespace Database\Seeders;

use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\Sala;
use App\Models\Funcion;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Crear géneros
        $accion = Genero::firstOrCreate(['nombre' => 'Acción']);
        $drama = Genero::firstOrCreate(['nombre' => 'Drama']);
        $comedia = Genero::firstOrCreate(['nombre' => 'Comedia']);

        // Crear películas
        $pelicula1 = Pelicula::firstOrCreate(
            ['titulo' => 'Avatar'],
            [
                'sinopsis' => 'Una épica de ciencia ficción en el planeta Pandora',
                'duracion' => 162,
                'poster_url' => 'https://via.placeholder.com/300x450?text=Avatar'
            ]
        );
        $pelicula1->generos()->syncWithoutDetaching([$accion->id]);

        $pelicula2 = Pelicula::firstOrCreate(
            ['titulo' => 'Titanic'],
            [
                'sinopsis' => 'Una historia de amor en el hundimiento del Titanic',
                'duracion' => 194,
                'poster_url' => 'https://via.placeholder.com/300x450?text=Titanic'
            ]
        );
        $pelicula2->generos()->syncWithoutDetaching([$drama->id]);

        $pelicula3 = Pelicula::firstOrCreate(
            ['titulo' => 'Toy Story'],
            [
                'sinopsis' => 'La aventura de dos juguetes que cobran vida',
                'duracion' => 81,
                'poster_url' => 'https://via.placeholder.com/300x450?text=ToyStory'
            ]
        );
        $pelicula3->generos()->syncWithoutDetaching([$comedia->id]);

        // Crear salas
        $sala1 = Sala::firstOrCreate(
            ['nombre' => 'Sala 1'],
            ['capacidad' => 60]
        );

        $sala2 = Sala::firstOrCreate(
            ['nombre' => 'Sala 2'],
            ['capacidad' => 40]
        );

        $sala3 = Sala::firstOrCreate(
            ['nombre' => 'Sala 3'],
            ['capacidad' => 50]
        );

        // Crear funciones (solo con campos requeridos)
        $ahora = now();

        // Funciones para Avatar
        Funcion::firstOrCreate(
            ['pelicula_id' => $pelicula1->id, 'sala_id' => $sala1->id, 'fecha' => $ahora->copy()->addHours(2)->format('Y-m-d H:i:s')],
            ['precio' => 8.50]
        );

        Funcion::firstOrCreate(
            ['pelicula_id' => $pelicula1->id, 'sala_id' => $sala2->id, 'fecha' => $ahora->copy()->addHours(5)->format('Y-m-d H:i:s')],
            ['precio' => 7.50]
        );

        // Funciones para Titanic
        Funcion::firstOrCreate(
            ['pelicula_id' => $pelicula2->id, 'sala_id' => $sala2->id, 'fecha' => $ahora->copy()->addHours(8)->format('Y-m-d H:i:s')],
            ['precio' => 7.00]
        );

        // Funciones para Toy Story
        Funcion::firstOrCreate(
            ['pelicula_id' => $pelicula3->id, 'sala_id' => $sala3->id, 'fecha' => $ahora->copy()->addHours(3)->format('Y-m-d H:i:s')],
            ['precio' => 6.50]
        );

        $this->command->info('✅ Datos de prueba creados exitosamente');
    }
}
