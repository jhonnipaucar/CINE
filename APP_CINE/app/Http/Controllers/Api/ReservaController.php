<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        return response()->json(
            Reserva::with(['funcion', 'user'])->get(),
            200
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|exists:funcions,id',
            'user_id' => 'required|exists:users,id',
            'asientos' => 'required|integer|min:1',
            'estado' => 'required|string',
            'comentarios' => 'nullable|string'
        ]);

        $reserva = Reserva::create($request->all());
        return response()->json($reserva, 201);
    }

    public function show($id)
    {
        $reserva = Reserva::with(['funcion', 'user'])->find($id);

        return $reserva
            ? response()->json($reserva)
            : response()->json(['message' => 'Reserva no encontrada'], 404);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $reserva->update($request->all());
        return response()->json($reserva);
    }

    public function destroy($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $reserva->delete();
        return response()->json(['message' => 'Reserva eliminada']);
    }
}
