<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use Illuminate\Http\Request;

class FuncionController extends Controller
{
    public function index()
    {
        return response()->json(
            Funcion::with(['pelicula', 'sala'])->get()
        , 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelicula_id' => 'required|exists:peliculas,id',
            'sala_id' => 'required|exists:salas,id',
            'fecha' => 'required|date',
            'precio' => 'required|numeric'
        ]);

        $funcion = Funcion::create($request->all());
        return response()->json($funcion, 201);
    }

    public function show($id)
    {
        $funcion = Funcion::with(['pelicula', 'sala'])->find($id);

        return $funcion
            ? response()->json($funcion)
            : response()->json(['message' => 'Función no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $funcion = Funcion::find($id);

        if (!$funcion) {
            return response()->json(['message' => 'Función no encontrada'], 404);
        }

        $funcion->update($request->all());
        return response()->json($funcion);
    }

    public function destroy($id)
    {
        $funcion = Funcion::find($id);

        if (!$funcion) {
            return response()->json(['message' => 'Función no encontrada'], 404);
        }

        $funcion->delete();

        return response()->json(['message' => 'Función eliminada']);
    }
}

