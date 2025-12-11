<?php

namespace App\Http\Controllers\Api;

use App\Models\Funcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FuncionController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Funcion::with(['pelicula', 'sala'])->get()
        ], 200);
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
            'fecha' => 'required|date_format:Y-m-d H:i:s',
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

