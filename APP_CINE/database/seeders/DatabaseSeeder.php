<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genero;
use App\Models\Pelicula;
use App\Models\Sala;
use App\Models\Funcion;
use App\Models\Reserva;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================
        // USUARIOS
        // ============================
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@cine.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        $cliente = User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@cine.com',
            'password' => bcrypt('cliente123'),
            'role' => 'usuario'
        ]);


        // ============================
        // GENEROS
        // ============================
        $generos = [
            'Acción', 'Aventura', 'Terror', 'Comedia',
            'Fantasía', 'Drama', 'Romance', 'Ciencia ficción'
        ];

        foreach ($generos as $g) {
            Genero::create(['nombre' => $g]);
        }


        // ============================
        // PELICULAS
        // ============================
        $peliculasData = [
            [
                'titulo' => 'Avengers: Endgame',
                'sinopsis' => 'Los Vengadores se reúnen para derrotar a Thanos.',
                'duracion' => 180,
                'poster_url' => 'https://image.tmdb.org/t/p/original/endgame.jpg',
                'tmdb_id' => 299534,
                'generos' => ['Acción', 'Ciencia ficción']
            ],
            [
                'titulo' => 'It',
                'sinopsis' => 'Un payaso aterrador aterroriza a un pueblo.',
                'duracion' => 135,
                'poster_url' => 'https://image.tmdb.org/t/p/original/it.jpg',
                'tmdb_id' => 346364,
                'generos' => ['Terror']
            ],
            [
                'titulo' => 'Titanic',
                'sinopsis' => 'Una historia de amor trágica en el famoso barco.',
                'duracion' => 195,
                'poster_url' => 'https://image.tmdb.org/t/p/original/titanic.jpg',
                'tmdb_id' => 597,
                'generos' => ['Drama', 'Romance']
            ]
        ];

        foreach ($peliculasData as $pData) {
            $pelicula = Pelicula::create([
                'titulo' => $pData['titulo'],
                'sinopsis' => $pData['sinopsis'],
                'duracion' => $pData['duracion'],
                'poster_url' => $pData['poster_url'],
                'tmdb_id' => $pData['tmdb_id']
            ]);

            // Relación con géneros
            $idsGeneros = Genero::whereIn('nombre', $pData['generos'])->pluck('id');
            $pelicula->generos()->attach($idsGeneros);
        }


        // ============================
        // SALAS
        // ============================
        $sala1 = Sala::create(['nombre' => 'Sala 1', 'capacidad' => 60]);
        $sala2 = Sala::create(['nombre' => 'Sala 2', 'capacidad' => 40]);


        // ============================
        // FUNCIONES
        // ============================
        $funcion1 = Funcion::create([
            'pelicula_id' => 1,
            'sala_id' => $sala1->id,
            'fecha' => now()->addDays(1)->setTime(16, 0),
            'precio' => 6.50
        ]);

        $funcion2 = Funcion::create([
            'pelicula_id' => 2,
            'sala_id' => $sala2->id,
            'fecha' => now()->addDays(1)->setTime(20, 0),
            'precio' => 7.00
        ]);


        // ============================
        // RESERVAS
        // ============================
        Reserva::create([
            'user_id' => $cliente->id,
            'funcion_id' => $funcion1->id,
            'asientos' => 2,
            'estado' => 'pendiente'
        ]);

        Reserva::create([
            'user_id' => $cliente->id,
            'funcion_id' => $funcion2->id,
            'asientos' => 1,
            'estado' => 'confirmada'
        ]);
    }
}
