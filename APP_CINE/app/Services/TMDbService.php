<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TMDbService
{
    private $apiKey;
    private $baseUrl = 'https://api.themoviedb.org/3';
    private $imageBaseUrl = 'https://image.tmdb.org/t/p/w500';

    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY', '');
    }

    /**
     * Buscar película por título
     */
    public function searchMovie($title)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_search_' . md5($title);
        
        // Cached por 7 días
        return Cache::remember($cacheKey, 60 * 60 * 24 * 7, function () use ($title) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'query' => $title,
                    'language' => 'es-ES'
                ]);

                if ($response->successful()) {
                    $results = $response->json()['results'] ?? [];
                    return array_slice($results, 0, 5); // Top 5 resultados
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Obtener detalles de película por TMDb ID
     */
    public function getMovieDetails($tmdbId)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_movie_' . $tmdbId;
        
        // Cached por 30 días
        return Cache::remember($cacheKey, 60 * 60 * 24 * 30, function () use ($tmdbId) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/{$tmdbId}", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES',
                    'append_to_response' => 'credits,reviews'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Películas populares
     */
    public function getPopularMovies($page = 1)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_popular_' . $page;
        
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($page) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/popular", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Películas próximas
     */
    public function getUpcomingMovies($page = 1)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_upcoming_' . $page;
        
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($page) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/upcoming", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Películas en cartelera
     */
    public function getNowPlayingMovies($page = 1)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_now_playing_' . $page;
        
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($page) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/now_playing", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Buscar películas
     */
    public function searchMovies($query, $page = 1)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_search_' . md5($query) . '_' . $page;
        
        return Cache::remember($cacheKey, 60 * 60 * 24 * 7, function () use ($query, $page) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                    'language' => 'es-ES',
                    'page' => $page
                ]);

                if ($response->successful()) {
                    return $response->json()['results'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Obtener películas por género
     */
    public function getMoviesByGenre($genreId, $page = 1)
    {
        if (!$this->apiKey) {
            return ['error' => 'TMDB API key no configurada'];
        }

        $cacheKey = 'tmdb_genre_' . $genreId . '_' . $page;
        
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($genreId, $page) {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/discover/movie", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES',
                    'with_genres' => $genreId,
                    'page' => $page,
                    'sort_by' => 'popularity.desc'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                return [];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        });
    }

    /**
     * Obtener géneros
     */
    public function getGenres()
    {
        if (!$this->apiKey) {
            return [];
        }

        $cacheKey = 'tmdb_genres';
        
        return Cache::remember($cacheKey, 60 * 60 * 24 * 30, function () {
            try {
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/genre/movie/list", [
                    'api_key' => $this->apiKey,
                    'language' => 'es-ES'
                ]);

                if ($response->successful()) {
                    return $response->json()['genres'] ?? [];
                }

                return [];
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    /**
     * Obtener URL pública de imagen
     */
    public function getImageUrl($path, $size = 'w500')
    {
        if (!$path) {
            return null;
        }
        return $this->imageBaseUrl . $path;
    }

    /**
     * Obtener URL de poster
     */
    public function getPosterUrl($posterPath)
    {
        return $this->getImageUrl($posterPath);
    }

    /**
     * Formatear respuesta de película
     */
    public function formatMovieResponse($movieData)
    {
        if (!$movieData || isset($movieData['error'])) {
            return null;
        }

        return [
            'id' => $movieData['id'] ?? null,
            'titulo' => $movieData['title'] ?? null,
            'sinopsis' => $movieData['overview'] ?? null,
            'calificacion' => $movieData['vote_average'] ?? 0,
            'votos' => $movieData['vote_count'] ?? 0,
            'fecha_lanzamiento' => $movieData['release_date'] ?? null,
            'poster' => $this->getPosterUrl($movieData['poster_path'] ?? null),
            'portada' => $this->getImageUrl($movieData['backdrop_path'] ?? null, 'w1280'),
            'duracion' => $movieData['runtime'] ?? null,
            'generos' => $movieData['genres'] ?? [],
            'idioma' => $movieData['original_language'] ?? null,
            'presupuesto' => $movieData['budget'] ?? null,
            'ingresos' => $movieData['revenue'] ?? null
        ];
    }
}
