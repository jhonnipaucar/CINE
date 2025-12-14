<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\PeliculaController;
use App\Http\Controllers\Api\FuncionController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\AdminReservaController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\AuthController;

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

// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Rutas para Películas (solo métodos que modifican datos)
    Route::post('/peliculas', [PeliculaController::class, 'store']);
    Route::put('/peliculas/{id}', [PeliculaController::class, 'update']);
    Route::delete('/peliculas/{id}', [PeliculaController::class, 'destroy']);
    
    // Rutas para Géneros (solo métodos que modifican datos)
    Route::post('/generos', [GeneroController::class, 'store']);
    Route::put('/generos/{id}', [GeneroController::class, 'update']);
    Route::delete('/generos/{id}', [GeneroController::class, 'destroy']);
    
    // Rutas para Salas (solo métodos que modifican datos)
    Route::post('/salas', [SalaController::class, 'store']);
    Route::put('/salas/{id}', [SalaController::class, 'update']);
    Route::delete('/salas/{id}', [SalaController::class, 'destroy']);
    
    // Rutas para Funciones (solo métodos que modifican datos)
    Route::post('/funciones', [FuncionController::class, 'store']);
    Route::put('/funciones/{id}', [FuncionController::class, 'update']);
    Route::delete('/funciones/{id}', [FuncionController::class, 'destroy']);
    
    // Rutas para Reservas
    Route::apiResource('reservas', ReservaController::class);
    
    // Rutas de Admin para Reservas
    Route::prefix('admin')->group(function () {
        Route::get('/reservas', [AdminReservaController::class, 'index']);
        Route::put('/reservas/{id}', [AdminReservaController::class, 'update']);
        Route::delete('/reservas/{id}', [AdminReservaController::class, 'destroy']);
    });
});

// Rutas públicas (solo lectura)
Route::get('/peliculas', [PeliculaController::class, 'index']);
Route::get('/peliculas/{id}', [PeliculaController::class, 'show']);
Route::get('/generos', [GeneroController::class, 'index']);
Route::get('/generos/{id}', [GeneroController::class, 'show']);
Route::get('/generos/{id}/peliculas', [GeneroController::class, 'peliculas']);
Route::get('/salas', [SalaController::class, 'index']);
Route::get('/salas/{id}', [SalaController::class, 'show']);
Route::get('/funciones', [FuncionController::class, 'index']);
Route::get('/funciones/{id}', [FuncionController::class, 'show']);