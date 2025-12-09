<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\PeliculaController;
use App\Http\Controllers\Api\FuncionController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TMDbController;

// Rutas de autenticación (públicas)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::put('/user', function (Request $request) {
    $user = $request->user();
    $user->update($request->only(['name', 'phone', 'bio']));
    return $user;
})->middleware('auth:sanctum');

Route::post('/change-password', function (Request $request) {
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = $request->user();
    
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['message' => 'Current password is incorrect'], 401);
    }

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return response()->json(['message' => 'Password updated successfully']);
})->middleware('auth:sanctum');

Route::delete('/user', function (Request $request) {
    $user = $request->user();
    $user->delete();
    return response()->json(['message' => 'Account deleted successfully']);
})->middleware('auth:sanctum');

// Rutas para TMDb (públicas - sin autenticación requerida) - ANTES que apiResource
Route::get('/peliculas-tmdb', [PeliculaController::class, 'indexTMDB']);
Route::get('/peliculas-tmdb/search', [PeliculaController::class, 'searchTMDB']);
Route::get('/peliculas-tmdb/{id}', [PeliculaController::class, 'showTMDB']);
Route::get('/tmdb/generos', [PeliculaController::class, 'genresTMDB']);
Route::get('/tmdb/generos/{genreId}/peliculas', [PeliculaController::class, 'genreMoviesTMDB']);

// Rutas para Películas (DESPUÉS de rutas TMDB)
Route::apiResource('peliculas', PeliculaController::class);
Route::post('/peliculas/{id}/upload-imagen', [PeliculaController::class, 'uploadImage'])->middleware('auth:sanctum');

// Rutas para Géneros
Route::get('/generos/{id}/peliculas', [GeneroController::class, 'peliculas']);
Route::apiResource('generos', GeneroController::class);

// Rutas para Salas
Route::apiResource('salas', SalaController::class);

// Rutas para Funciones
Route::apiResource('funciones', FuncionController::class);

// Rutas para Reservas
Route::apiResource('reservas', ReservaController::class);