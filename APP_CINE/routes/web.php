<?php

use Illuminate\Support\Facades\Route;

// Ruta de bienvenida - redirige a dashboard si está autenticado
Route::get('/', function () {
    // Si hay un token en localStorage (desde el frontend), mostrará el dashboard
    // Si no hay token, mostrará la página de bienvenida con opción de login
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Ruta del dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Rutas protegidas (validadas por localStorage en el frontend)
Route::get('/peliculas', function () {
    return view('catalogo');
})->name('peliculas');

Route::get('/catalogo', function () {
    return view('catalogo');
})->name('catalogo');

Route::get('/reservas', function () {
    return view('reservas');
})->name('reservas');

Route::get('/generos', function () {
    return view('generos');
})->name('generos');

Route::get('/funciones', function () {
    return view('funciones');
})->name('funciones');

Route::get('/salas', function () {
    return view('salas');
})->name('salas');

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

// Rutas de Admin (protegidas en el frontend con localStorage)
Route::middleware([\App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::get('/peliculas', function () {
            return view('admin.peliculas');
        })->name('peliculas');
        
        Route::get('/generos', function () {
            return view('admin.generos');
        })->name('generos');
        
        Route::get('/salas', function () {
            return view('admin.salas');
        })->name('salas');
        
        Route::get('/funciones', function () {
            return view('admin.funciones');
        })->name('funciones');
        
        Route::get('/reservas', function () {
            return view('admin.reservas');
        })->name('reservas');
    });
