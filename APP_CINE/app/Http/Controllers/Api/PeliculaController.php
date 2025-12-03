<?php

namespace App\Http\Controllers;

use App\Models\Pelicula;
use Illuminate\Http\Request;

class PeliculaController extends Controller
{
    public function index()
    {
        return response()->json(Pelicula::all(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'sinopsis' => 'required|string',
            'duracion' => 'required|integer',
            'poster_url' => 'required|string'
        ]);

        $pelicula = Pelicula::create($request->all());
        return response()->json($pelicula, 201);
    }

    public function show($id)
    {
        $pelicula = Pelicula::find($id);
        return $pelicula
            ? response()->json($pelicula, 200)
            : response()->json(['message' => 'Pelicula no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $pelicula->update($request->all());
        return response()->json($pelicula, 200);
    }

    public function destroy($id)
    {
        $pelicula = Pelicula::find($id);

        if (!$pelicula) {
            return response()->json(['message' => 'Pelicula no encontrada'], 404);
        }

        $pelicula->delete();
        return response()->json(['message' => 'Pelicula eliminada'], 200);
    }
}
