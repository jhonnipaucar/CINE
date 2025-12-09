<?php

namespace App\Console\Commands;

use App\Models\Pelicula;
use App\Services\TMDbService;
use Illuminate\Console\Command;

class ImportMoviesFromTMDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:import {--popular : Importar películas populares} {--limit=20 : Límite de películas a importar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar películas desde TMDb y enriquecer datos locales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tmdbService = app(TMDbService::class);

        if (!env('TMDB_API_KEY')) {
            $this->error('TMDB_API_KEY no configurada en .env');
            return 1;
        }

        if ($this->option('popular')) {
            $this->importPopular($tmdbService);
        } else {
            $this->enrichExistingMovies($tmdbService);
        }

        return 0;
    }

    /**
     * Importar películas populares desde TMDb
     */
    private function importPopular($tmdbService)
    {
        $limit = $this->option('limit');
        $this->info("Importando hasta {$limit} películas populares de TMDb...");

        $response = $tmdbService->getPopularMovies();
        $results = $response['results'] ?? [];

        $count = 0;
        foreach (array_slice($results, 0, $limit) as $movieData) {
            if ($count >= $limit) break;

            try {
                $formatted = $tmdbService->formatMovieResponse($movieData);

                // Buscar por título para evitar duplicados
                $existingMovie = Pelicula::where('titulo', $formatted['titulo'])->first();

                if ($existingMovie) {
                    // Enriquecer película existente
                    $existingMovie->update([
                        'calificacion_tmdb' => $formatted['calificacion'],
                        'votos_tmdb' => $formatted['votos'],
                        'tmdb_id' => $formatted['id'],
                        'descripcion' => $formatted['sinopsis'],
                        'url_imagen' => $formatted['poster'] ?? $existingMovie->url_imagen,
                    ]);

                    $this->info("✓ Actualizada: {$formatted['titulo']}");
                } else {
                    // Crear nueva película
                    Pelicula::create([
                        'titulo' => $formatted['titulo'],
                        'sinopsis' => $formatted['sinopsis'] ?? '',
                        'descripcion' => $formatted['sinopsis'],
                        'duracion' => $formatted['duracion'] ?? 0,
                        'url_imagen' => $formatted['poster'],
                        'tmdb_id' => $formatted['id'],
                        'calificacion_tmdb' => $formatted['calificacion'],
                        'votos_tmdb' => $formatted['votos'],
                    ]);

                    $this->info("✓ Creada: {$formatted['titulo']}");
                }

                $count++;
            } catch (\Exception $e) {
                $this->error("Error procesando película: {$e->getMessage()}");
            }
        }

        $this->info("Importación completada. {$count} películas procesadas.");
    }

    /**
     * Enriquecer películas existentes con datos de TMDb
     */
    private function enrichExistingMovies($tmdbService)
    {
        $this->info('Enriqueciendo películas existentes con datos de TMDb...');

        $peliculas = Pelicula::whereNull('calificacion_tmdb')->get();

        foreach ($peliculas as $pelicula) {
            try {
                // Buscar por título
                $results = $tmdbService->searchMovie($pelicula->titulo);

                if (!empty($results) && !isset($results['error'])) {
                    $tmdbMovie = $results[0];
                    $formatted = $tmdbService->formatMovieResponse($tmdbMovie);

                    $pelicula->update([
                        'calificacion_tmdb' => $formatted['calificacion'],
                        'votos_tmdb' => $formatted['votos'],
                        'tmdb_id' => $formatted['id'],
                        'descripcion' => $formatted['sinopsis'] ?? $pelicula->descripcion,
                    ]);

                    $this->info("✓ Enriquecida: {$pelicula->titulo}");
                } else {
                    $this->warn("⚠ No encontrada en TMDb: {$pelicula->titulo}");
                }

                // Respetar límite de rate limit
                sleep(1);
            } catch (\Exception $e) {
                $this->error("Error enriqueciendo {$pelicula->titulo}: {$e->getMessage()}");
            }
        }

        $this->info('Enriquecimiento completado.');
    }
}
