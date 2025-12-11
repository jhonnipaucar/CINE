<?php

use Illuminate\Support\Facades\DB;

// Conectar a la base de datos y listar usuarios
try {
    $usuarios = DB::table('users')->select('id', 'name', 'email', 'role')->get();
    
    if ($usuarios->isEmpty()) {
        echo "No hay usuarios en la base de datos\n";
    } else {
        echo "Usuarios registrados:\n";
        echo "ID | Nombre | Email | Rol\n";
        echo str_repeat("-", 60) . "\n";
        foreach ($usuarios as $usuario) {
            echo "{$usuario->id} | {$usuario->name} | {$usuario->email} | {$usuario->role}\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
