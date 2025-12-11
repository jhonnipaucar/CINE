<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelicula;
use App\Services\TMDbService;
use Illuminate\Http\Request;

class TMDbController extends Controller
{
    private $tmdbService;

    public function __construct(TMDbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Buscar película en TMDb
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $results = $this->tmdbService->searchMovie($request->query);
        
        $formatted = collect($results)->map(function ($movie) {
            return $this->tmdbService->formatMovieResponse($movie);
        })->filter()->toArray();

        return response()->json(['data' => $formatted]);
    }

    /**
     * Obtener detalles de película en TMDb
     */
    public function show($id)
    {
        $movieData = $this->tmdbService->getMovieDetails($id);
        
        if (!$movieData) {
            return response()->json(['error' => 'Película no encontrada'], 404);
        }

        $formatted = $this->tmdbService->formatMovieResponse($movieData);
        
        return response()->json(['data' => $formatted]);
    }

    /**
     * Películas populares
     */
    public function popular(Request $request)
    {
        $page = $request->get('page', 1);
        $response = $this->tmdbService->getPopularMovies($page);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        $formatted = collect($response['results'] ?? [])->map(function ($movie) {
            return $this->tmdbService->formatMovieResponse($movie);
        })->filter()->toArray();

        return response()->json([
            'data' => $formatted,
            'page' => $response['page'] ?? 1,
            'total_pages' => $response['total_pages'] ?? 1,
            'total_results' => $response['total_results'] ?? 0
        ]);
    }

    /**
     * Películas próximas
     */
    public function upcoming(Request $request)
    {
        $page = $request->get('page', 1);
        $response = $this->tmdbService->getUpcomingMovies($page);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        $formatted = collect($response['results'] ?? [])->map(function ($movie) {
            return $this->tmdbService->formatMovieResponse($movie);
        })->filter()->toArray();

        return response()->json([
            'data' => $formatted,
            'page' => $response['page'] ?? 1,
            'total_pages' => $response['total_pages'] ?? 1,
            'total_results' => $response['total_results'] ?? 0
        ]);
    }

    /**
     * Enriquecer película local con datos de TMDb
     */
    public function enrichPelicula(Request $request, $id)
    {
        $request->validate([
            'tmdb_id' => 'required|integer'
        ]);

        $pelicula = Pelicula::findOrFail($id);

        // Buscar en TMDb
        $tmdbData = $this->tmdbService->getMovieDetails($request->tmdb_id);
        
        if (!$tmdbData) {
            return response()->json(['error' => 'Película no encontrada en TMDb'], 404);
        }

        // Actualizar película con datos de TMDb
        $pelicula->update([
            'descripcion' => $tmdbData['overview'] ?? $pelicula->descripcion,
            'calificacion_tmdb' => $tmdbData['vote_average'] ?? null,
            'votos_tmdb' => $tmdbData['vote_count'] ?? null,
            'tmdb_id' => $request->tmdb_id,
            'url_imagen' => $this->tmdbService->getPosterUrl($tmdbData['poster_path'] ?? null) ?? $pelicula->url_imagen,
        ]);

        return response()->json([
            'data' => $pelicula->load('generos'),
            'message' => 'Película enriquecida con datos de TMDb'
        ]);
    }
}
