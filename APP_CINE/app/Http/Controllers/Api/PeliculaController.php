<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelicula;
use App\Services\FirebaseStorageService;
use App\Services\TMDbService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FirebaseService;

class PeliculaController extends Controller
{
    /**
     * Obtener películas de base de datos local
     */
    public function index()
    {
        $peliculas = Pelicula::with('generos')->get();
        return response()->json([
            'data' => $peliculas
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para crear películas'], 403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'url_imagen' => 'nullable|string|url'
        ]);

        $pelicula = Pelicula::create($request->only(['titulo', 'sinopsis', 'duracion', 'url_imagen']));
        
        return response()->json([
            'data' => $pelicula->load('generos')
        ], 201);
    }

    public function show($id)
    {
        $pelicula = Pelicula::with('generos')->find($id);
        return $pelicula
            ? response()->json([
                'data' => $pelicula
            ], 200)
            : response()->json(['message' => 'Pelicula no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para editar películas'], 403);
        }

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $request->validate([
            'titulo' => 'string|max:255',
            'sinopsis' => 'string',
            'duracion' => 'integer|min:1',
            'url_imagen' => 'nullable|string|url'
        ]);

        $pelicula->update($request->only(['titulo', 'sinopsis', 'duracion', 'url_imagen']));
        
        return response()->json([
            'data' => $pelicula->load('generos')
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para eliminar películas'], 403);
        }

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $pelicula->delete();
        return response()->json(['message' => 'Pelicula eliminada'], 200);
    }

    /**
     * Carga una imagen de película a Firebase Storage
     */
    public function uploadImage(Request $request, $id)
    {
        // Verificar si es admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No tienes permiso para subir imágenes'], 403);
        }

        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB máximo
        ]);

        try {
            $firebaseService = new FirebaseService();
            $url = $firebaseService->uploadImage($request->file('imagen'), 'peliculas');
            
            // Actualizar la URL en la base de datos
            $pelicula->update(['url_imagen' => $url]);

            return response()->json([
                'message' => 'Imagen subida exitosamente',
                'url' => $url,
                'data' => $pelicula
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

