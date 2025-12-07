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
