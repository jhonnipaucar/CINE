<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\PeliculaController;

Route::get('/peliculas', [PeliculaController::class, 'index']);
Route::get('/funcions', [App\Http\Controllers\Api\FuncionController::class, 'index']);
Route::get('/generos', [App\Http\Controllers\Api\GeneroController::class, 'index']);
Route::get('/reservas', [App\Http\Controllers\Api\ReservaController::class, 'index']);
Route::get('/salas', [App\Http\Controllers\Api\SalaController::class, 'index']);