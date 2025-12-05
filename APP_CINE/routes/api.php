<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeliculaController;
use App\Http\Controllers\Api\FuncionController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\AuthController;

// Rutas de autenticación (públicas)
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas para Películas
Route::apiResource('peliculas', PeliculaController::class);

// Rutas para Géneros
Route::apiResource('generos', GeneroController::class);

// Rutas para Salas
Route::apiResource('salas', SalaController::class);

// Rutas para Funciones
Route::apiResource('funciones', FuncionController::class);

// Rutas para Reservas
Route::apiResource('reservas', ReservaController::class);