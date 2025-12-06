<?php

namespace App\Http\Controllers\Api;

use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ReservaController extends Controller
{
    public function index(Request $request)
{
    // 🚨 Bloque de seguridad para el rol 'admin'
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Acceso denegado. Se requiere rol de administrador.'], Response::HTTP_FORBIDDEN);
    }
    
    // Si es admin, lista todas las reservas
    $reservas = Reserva::with('user', 'funcion')->get();
    return response()->json($reservas);
}

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|exists:funciones,id',
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

    public function update(Request $request, Reserva $reserva)
{
    // 🚨 Bloque de seguridad para el rol 'admin'
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Acceso denegado. Se requiere rol de administrador.'], Response::HTTP_FORBIDDEN);
    }

    // Validación para el cambio de estado
    $request->validate(['status' => 'required|in:pendiente,aceptada,rechazada']);
    
    // Actualización
    $reserva->update(['status' => $request->status]);

    return response()->json([
        'message' => 'Estado de reserva actualizado a ' . $reserva->status,
        'reserva' => $reserva
    ]);
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
