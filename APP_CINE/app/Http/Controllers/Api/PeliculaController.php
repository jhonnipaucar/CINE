<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelicula; // Asegúrate de que el nombre del modelo sea correcto (Pelicula o Service)
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PeliculaController extends Controller
{
    /**
     * Muestra una lista de todas las películas (Catálogo Público).
     * Ruta: GET /api/servicios
     * Accesible por cualquier persona (pública).
     */
    public function index(Request $request)
    {
        // Lógica de Búsqueda y Filtrado (Requisito del proyecto)
        $query = Pelicula::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
        }

        $peliculas = $query->latest()->get();

        return response()->json($peliculas);
    }

    /**
     * Crea una nueva película (Servicio).
     * Ruta: POST /api/servicios
     * Protegida: Solo para el rol 'admin'.
     */
    public function store(Request $request)
{
    // 🚨 1. VERIFICACIÓN DE ROL
    if (auth()->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Acceso denegado. Se requiere rol de administrador.'
        ], Response::HTTP_FORBIDDEN);
    }

    // 2. VALIDACIÓN (Crucial para que la petición POST funcione)
    $request->validate([
        'title' => 'required|string|max:255',
        // ... otros campos
        'image_url' => 'required|url',
    ]);

    // 3. CREACIÓN
    $pelicula = Pelicula::create($request->all());

    return response()->json([
        'message' => 'Película creada con éxito.',
        'pelicula' => $pelicula
    ], Response::HTTP_CREATED);
}

    /**
     * Muestra el detalle de una película específica.
     * Ruta: GET /api/servicios/{id}
     * Accesible por cualquier persona (pública).
     */
    public function show(Pelicula $pelicula)
    {
        // Aquí podrías agregar la lógica para consumir la API externa (TMDb)
        // y adjuntar los datos de actores o puntuación antes de retornar.

        return response()->json($pelicula);
    }

    /**
     * Actualiza una película existente.
     * Ruta: PUT/PATCH /api/servicios/{id}
     * Protegida: Solo para el rol 'admin'.
     */
    public function update(Request $request, Pelicula $pelicula)
    {
        // 1. VERIFICACIÓN DE ROL (Seguridad Crítica)
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Se requiere rol de administrador.'
            ], Response::HTTP_FORBIDDEN);
        }

        // 2. VALIDACIÓN DE DATOS
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'image_url' => 'sometimes|url',
        ]);

        // 3. ACTUALIZACIÓN DEL RECURSO
        $pelicula->update($request->all());

        // 4. RESPUESTA
        return response()->json([
            'message' => 'Película actualizada con éxito.',
            'pelicula' => $pelicula
        ]);
    }

    /**
     * Elimina una película específica.
     * Ruta: DELETE /api/servicios/{id}
     * Protegida: Solo para el rol 'admin'.
     */
    public function destroy(Pelicula $pelicula)
    {
        // 1. VERIFICACIÓN DE ROL (Seguridad Crítica)
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Se requiere rol de administrador.'
            ], Response::HTTP_FORBIDDEN);
        }

        // 2. ELIMINACIÓN DEL RECURSO
        $pelicula->delete();

        // 3. RESPUESTA
        return response()->json([
            'message' => 'Película eliminada con éxito.'
        ], Response::HTTP_NO_CONTENT); // 204 No Content es estándar para DELETE exitoso
    }
}