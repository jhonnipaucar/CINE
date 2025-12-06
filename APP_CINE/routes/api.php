<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeliculaController;
use App\Http\Controllers\Api\FuncionController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\SalaController;
use App\Http\Controllers\Api\AuthController;

// =========================================================
// 1. RUTAS PÚBLICAS (Catálogo y Autenticación)
// =========================================================

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rutas de Catálogo (Listar y Detalle de Servicios/Películas)
// Los servicios se deben poder explorar sin iniciar sesión 
Route::get('/servicios', [PeliculaController::class, 'index']); // Catálogo
Route::get('/servicios/{id}', [PeliculaController::class, 'show']); // Detalle

// Si necesitas listar géneros o funciones públicas, van aquí:
Route::get('/generos', [GeneroController::class, 'index']);
Route::get('/funciones', [FuncionController::class, 'index']);


// =========================================================
// 2. RUTAS DE USUARIO AUTENTICADO (Crear Solicitudes)
// Requiere Token. La verificación de rol 'admin' NO se aplica.
// =========================================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Cierre de sesión
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Crear una solicitud de reserva/pedido/cita [cite: 47]
    // Solo un usuario logueado puede crear reservas
    Route::post('/reservas', [ReservaController::class, 'store']); 
    
    // El usuario también puede ver sus propias reservas (si implementaste la lógica en el controlador)
    Route::get('/reservas/mis-reservas', [ReservaController::class, 'userIndex']); 
});


// =========================================================
// 3. RUTAS DE ADMINISTRADOR (CRUD y Gestión de Reservas)
// Requiere Token Y la verificación de rol 'admin' en el controlador.
// =========================================================
Route::middleware('auth:sanctum')->group(function () {

    // CRUD de Servicios/Películas [cite: 37]
    Route::post('/servicios', [PeliculaController::class, 'store']); // Crear
    Route::put('/servicios/{id}', [PeliculaController::class, 'update']); // Editar
    Route::delete('/servicios/{id}', [PeliculaController::class, 'destroy']); // Eliminar
    
    // El administrador puede ver todas las solicitudes y cambiar su estado [cite: 52, 53]
    Route::get('/reservas', [ReservaController::class, 'index']); // Listar TODAS
    Route::patch('/reservas/{id}', [ReservaController::class, 'update']); // Cambiar estado
    
    // CRUD para otros recursos de Admin:
    Route::apiResource('funciones', FuncionController::class)->except(['index', 'show']);
    Route::apiResource('generos', GeneroController::class)->except(['index', 'show']);
    Route::apiResource('salas', SalaController::class);
});