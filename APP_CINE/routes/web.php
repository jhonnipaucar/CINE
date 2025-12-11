<?php

use Illuminate\Support\Facades\Route;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

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

// Rutas de Admin (sin middleware, el control se hace en JavaScript con token)
Route::get('/admin/peliculas', function () {
    return view('admin.peliculas');
})->name('admin.peliculas');

Route::get('/admin/reservas', function () {
    return view('admin.reservas');
})->name('admin.reservas');
