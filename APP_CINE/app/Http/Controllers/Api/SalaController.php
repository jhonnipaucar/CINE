<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    public function index()
    {
        return response()->json(Sala::all(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'capacidad' => 'required|integer'
        ]);

        $sala = Sala::create($request->all());
        return response()->json($sala, 201);
    }

    public function show($id)
    {
        $sala = Sala::find($id);

        return $sala
            ? response()->json($sala)
            : response()->json(['message' => 'Sala no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $sala = Sala::find($id);

        if (!$sala) {
            return response()->json(['message' => 'Sala no encontrada'], 404);
        }

        $sala->update($request->all());
        return response()->json($sala);
    }

    public function destroy($id)
    {
        $sala = Sala::find($id);

        if (!$sala) {
            return response()->json(['message' => 'Sala no encontrada'], 404);
        }

        $sala->delete();
        return response()->json(['message' => 'Sala eliminada']);
    }
}
