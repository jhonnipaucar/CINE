<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Asegúrate de importar tu modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Maneja el registro de nuevos usuarios.
     * Ruta: POST /api/auth/register
     */
    public function register(Request $request)
    {
        // 1. Validación de Datos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Creación del Usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'usuario', // Asignación de rol por defecto
        ]);

        // 3. Generación del Token
        // 'auth_token' es el nombre del token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Respuesta JSON
        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // Es crucial devolver el rol
            ]
        ], 201);
    }

    /**
     * Maneja el inicio de sesión del usuario.
     * Ruta: POST /api/auth/login
     */
    public function login(Request $request)
    {
        // 1. Validación de Datos
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        // 2. Verificación de Credenciales
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Lanza una excepción de validación para retornar un JSON con 422
            throw ValidationException::withMessages([
                'message' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // 3. Generación del Token (borra tokens previos del mismo usuario por seguridad)
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Respuesta JSON
        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role, // Esencial para el Frontend
            ]
        ]);
    }

    /**
     * Cierra la sesión del usuario revoca el token.
     * Ruta: POST /api/auth/logout (Requiere middleware auth:sanctum)
     */
    public function logout(Request $request)
    {
        // Revoca el token de acceso actual que se usó para esta solicitud
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada con éxito. Token revocado.'
        ]);
    }
}