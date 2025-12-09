<?php

namespace App\Http\Controllers\Api;

use App\Models\Genero;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class GeneroController extends Controller
{
    public function index()
    {
        return response()->json(Genero::with('peliculas')->get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);

        $genero = Genero::create($request->all());
        return response()->json($genero, 201);
    }

    public function show($id)
    {
        $genero = Genero::find($id);

        return $genero
            ? response()->json($genero)
            : response()->json(['message' => 'Género no encontrado'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $genero = Genero::find($id);

        if (!$genero) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        $genero->update($request->all());
        return response()->json($genero, 200);
    }

    public function destroy($id)
    {
        $genero = Genero::find($id);

        if (!$genero) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        $genero->delete();
        return response()->json(['message' => 'Género eliminado'], 200);
    }

    // Obtener películas de un género
    public function peliculas($id)
    {
        $genero = Genero::find($id);

        if (!$genero) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        $peliculas = $genero->peliculas()->get();
        return response()->json($peliculas, 200);
    }
}
