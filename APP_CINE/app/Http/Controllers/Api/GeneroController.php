<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use Illuminate\Http\Request;

class GeneroController extends Controller
{
    public function index()
    {
        return response()->json(Genero::all(), 200);
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
}
