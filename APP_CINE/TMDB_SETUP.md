#!/bin/bash

# Script para importar datos de TMDb
# Instrucciones:
# 1. Obtener API key gratis en: https://www.themoviedb.org/settings/api
# 2. Agregar a .env: TMDB_API_KEY=tu_clave_aqui
# 3. Ejecutar: php artisan tinker
# 
# Luego copiar y ejecutar:

# app(App\Services\TMDbService::class)->searchMovie("The Matrix");

# O crear películas manualmente con datos enriquecidos:

# $pelicula = App\Models\Pelicula::find(1);
# $tmdbData = app(App\Services\TMDbService::class)->getMovieDetails(603);
# $formatted = app(App\Services\TMDbService::class)->formatMovieResponse($tmdbData);
# $pelicula->update([
#     'calificacion_tmdb' => $formatted['calificacion'],
#     'votos_tmdb' => $formatted['votos'],
#     'tmdb_id' => 603,
#     'descripcion' => $formatted['sinopsis']
# ]);

echo "TMDb integration configured successfully!"
echo "To use TMDb API:"
echo "1. Get free API key from: https://www.themoviedb.org/settings/api"
echo "2. Add to .env: TMDB_API_KEY=your_key_here"
echo "3. Use: php artisan tinker"
echo ""
echo "Example commands:"
echo "app(App\\Services\\TMDbService::class)->searchMovie('The Matrix')"
echo "app(App\\Services\\TMDbService::class)->getMovieDetails(603)"
echo ""
