<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelicula;
use App\Services\FirebaseStorageService;
use App\Services\TMDbService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeliculaController extends Controller
{
    protected $tmdbService;

    public function __construct(TMDbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Obtener películas populares de TMDB
     */
    public function indexTMDB(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $type = $request->get('type', 'popular'); // popular, upcoming, now_playing

            $response = match($type) {
                'upcoming' => $this->tmdbService->getUpcomingMovies($page),
                'now_playing' => $this->tmdbService->getNowPlayingMovies($page),
                default => $this->tmdbService->getPopularMovies($page)
            };

            if (isset($response['error'])) {
                \Log::error('TMDB Error: ' . $response['error']);
                return response()->json([
                    'message' => 'Error al obtener películas',
                    'error' => $response['error']
                ], 500);
            }

            if (empty($response)) {
                return response()->json([
                    'message' => 'No hay películas disponibles',
                    'data' => []
                ], 200);
            }

            $movies = $response['results'] ?? [];
            $formattedMovies = array_map(function ($movie) {
                return [
                    'id' => $movie['id'],
                    'tmdb_id' => $movie['id'],
                    'titulo' => $movie['title'],
                    'sinopsis' => $movie['overview'],
                    'calificacion' => round($movie['vote_average'], 1),
                    'votos' => $movie['vote_count'],
                    'fecha_lanzamiento' => $movie['release_date'],
                    'poster_url' => $this->tmdbService->getPosterUrl($movie['poster_path']),
                    'url_imagen' => $this->tmdbService->getPosterUrl($movie['poster_path']),
                    'backdrop_url' => $this->tmdbService->getImageUrl($movie['backdrop_path'], 'w1280'),
                    'generos' => $movie['genre_ids'] ?? [],
                    'popularidad' => $movie['popularity']
                ];
            }, $movies);

            return response()->json([
                'data' => $formattedMovies,
                'page' => $page,
                'total_pages' => $response['total_pages'] ?? 1,
                'total_results' => $response['total_results'] ?? 0
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Exception in indexTMDB: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener películas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar película en TMDB
     */
    public function searchTMDB(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'message' => 'La búsqueda debe tener al menos 2 caracteres'
            ], 400);
        }

        $page = $request->get('page', 1);
        $results = $this->tmdbService->searchMovies($query, $page);

        if (isset($results['error'])) {
            return response()->json([
                'message' => 'Error al buscar películas',
                'error' => $results['error']
            ], 500);
        }

        $formattedMovies = array_map(function ($movie) {
            return [
                'id' => $movie['id'],
                'tmdb_id' => $movie['id'],
                'titulo' => $movie['title'],
                'sinopsis' => $movie['overview'],
                'calificacion' => round($movie['vote_average'], 1),
                'votos' => $movie['vote_count'],
                'fecha_lanzamiento' => $movie['release_date'],
                'poster_url' => $this->tmdbService->getPosterUrl($movie['poster_path']),
                'url_imagen' => $this->tmdbService->getPosterUrl($movie['poster_path'])
            ];
        }, $results);

        return response()->json([
            'data' => $formattedMovies
        ], 200);
    }

    /**
     * Obtener detalles de película de TMDB
     */
    public function showTMDB($id)
    {
        $movieData = $this->tmdbService->getMovieDetails($id);

        if (!$movieData || isset($movieData['error'])) {
            return response()->json([
                'message' => 'Película no encontrada',
                'error' => $movieData['error'] ?? 'Error desconocido'
            ], 404);
        }

        $generos = array_map(function ($genre) {
            return [
                'id' => $genre['id'],
                'nombre' => $genre['name']
            ];
        }, $movieData['genres'] ?? []);

        $directores = array_map(function ($crew) {
            return $crew['name'];
        }, array_filter($movieData['credits']['crew'] ?? [], function ($crew) {
            return $crew['job'] === 'Director';
        }));

        $actores = array_map(function ($cast) {
            return [
                'nombre' => $cast['name'],
                'personaje' => $cast['character'],
                'foto' => $this->tmdbService->getImageUrl($cast['profile_path'])
            ];
        }, array_slice($movieData['credits']['cast'] ?? [], 0, 10));

        return response()->json([
            'data' => [
                'id' => $movieData['id'],
                'tmdb_id' => $movieData['id'],
                'titulo' => $movieData['title'],
                'sinopsis' => $movieData['overview'],
                'descripcion' => $movieData['overview'],
                'calificacion' => round($movieData['vote_average'], 1),
                'votos' => $movieData['vote_count'],
                'fecha_lanzamiento' => $movieData['release_date'],
                'duracion' => $movieData['runtime'],
                'poster_url' => $this->tmdbService->getPosterUrl($movieData['poster_path']),
                'url_imagen' => $this->tmdbService->getPosterUrl($movieData['poster_path']),
                'backdrop_url' => $this->tmdbService->getImageUrl($movieData['backdrop_path'], 'w1280'),
                'presupuesto' => $movieData['budget'],
                'ingresos' => $movieData['revenue'],
                'idioma_original' => $movieData['original_language'],
                'estado' => $movieData['status'],
                'generos' => $generos,
                'directores' => $directores,
                'actores' => $actores
            ]
        ], 200);
    }

    /**
     * Películas por género de TMDB
     */
    public function genreMoviesTMDB($genreId, Request $request)
    {
        $page = $request->get('page', 1);
        $movies = $this->tmdbService->getMoviesByGenre($genreId, $page);

        if (isset($movies['error'])) {
            return response()->json([
                'message' => 'Error al obtener películas por género',
                'error' => $movies['error']
            ], 500);
        }

        $results = $movies['results'] ?? [];
        $formattedMovies = array_map(function ($movie) {
            return [
                'id' => $movie['id'],
                'tmdb_id' => $movie['id'],
                'titulo' => $movie['title'],
                'sinopsis' => $movie['overview'],
                'calificacion' => round($movie['vote_average'], 1),
                'votos' => $movie['vote_count'],
                'fecha_lanzamiento' => $movie['release_date'],
                'poster_url' => $this->tmdbService->getPosterUrl($movie['poster_path']),
                'url_imagen' => $this->tmdbService->getPosterUrl($movie['poster_path']),
                'generos' => $movie['genre_ids'] ?? []
            ];
        }, $results);

        return response()->json([
            'data' => $formattedMovies,
            'page' => $page,
            'total_pages' => $movies['total_pages'] ?? 1
        ], 200);
    }

    /**
     * Obtener géneros de TMDB
     */
    public function genresTMDB()
    {
        $generos = $this->tmdbService->getGenres();

        if (empty($generos)) {
            return response()->json([
                'message' => 'Error al obtener géneros'
            ], 500);
        }

        return response()->json([
            'data' => $generos
        ], 200);
    }

    // ==================== MÉTODOS LOCALES (Basados en BD) ====================

    /**
     * Obtener películas de base de datos local
     */
    public function index()
    {
        $peliculas = Pelicula::with('generos')->get();
        return response()->json([
            'data' => $peliculas
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para crear películas'], 403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'url_imagen' => 'nullable|string|url'
        ]);

        $pelicula = Pelicula::create($request->only(['titulo', 'sinopsis', 'duracion', 'url_imagen']));
        
        return response()->json([
            'data' => $pelicula->load('generos')
        ], 201);
    }

    public function show($id)
    {
        $pelicula = Pelicula::with('generos')->find($id);
        return $pelicula
            ? response()->json([
                'data' => $pelicula
            ], 200)
            : response()->json(['message' => 'Pelicula no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para editar películas'], 403);
        }

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $request->validate([
            'titulo' => 'string|max:255',
            'sinopsis' => 'string',
            'duracion' => 'integer|min:1',
            'url_imagen' => 'nullable|string|url'
        ]);

        $pelicula->update($request->only(['titulo', 'sinopsis', 'duracion', 'url_imagen']));
        
        return response()->json([
            'data' => $pelicula->load('generos')
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para eliminar películas'], 403);
        }

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $pelicula->delete();
        return response()->json(['message' => 'Pelicula eliminada'], 200);
    }

    /**
     * Subir imagen a Firebase Storage
     */
    public function uploadImage(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para subir imágenes'], 403);
        }

        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ]);

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        try {
            $resultado = FirebaseStorageService::uploadImage($request->file('imagen'), 'peliculas');

            if (!$resultado['success']) {
                return response()->json([
                    'message' => 'Error al subir imagen: ' . $resultado['error']
                ], 400);
            }

            // Actualizar URL en la película
            $pelicula->update([
                'url_imagen' => $resultado['url']
            ]);

            return response()->json([
                'message' => 'Imagen subida correctamente',
                'data' => [
                    'url' => $resultado['url'],
                    'pelicula' => $pelicula
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}

