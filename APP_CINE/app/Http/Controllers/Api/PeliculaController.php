<?php

namespace App\Http\Controllers\Api;

use App\Models\Pelicula;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            'poster_url' => 'nullable|string',
            'genero_id' => 'required|exists:generos,id'
        ]);

        $pelicula = Pelicula::create([
            'titulo' => $request->titulo,
            'sinopsis' => $request->sinopsis,
            'duracion' => $request->duracion,
            'poster_url' => $request->poster_url ?? $request->url_imagen
        ]);
        
        // Agregar género a la película
        if ($request->genero_id) {
            $pelicula->generos()->attach($request->genero_id);
        }
        
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
            'poster_url' => 'nullable|string',
            'genero_id' => 'nullable|exists:generos,id'
        ]);

        $pelicula->update([
            'titulo' => $request->titulo ?? $pelicula->titulo,
            'sinopsis' => $request->sinopsis ?? $pelicula->sinopsis,
            'duracion' => $request->duracion ?? $pelicula->duracion,
            'poster_url' => $request->poster_url ?? $request->url_imagen ?? $pelicula->poster_url
        ]);
        
        // Actualizar género si se proporciona
        if ($request->genero_id) {
            $pelicula->generos()->sync([$request->genero_id]);
        }
        
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
}

