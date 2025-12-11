<?php

namespace App\Http\Controllers\Api;

use App\Models\Reserva;
use App\Models\Funcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $reservas = Reserva::with(['funcion.pelicula', 'funcion.sala', 'user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $reservas
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'funcion_id' => 'required|exists:funciones,id',
            'asientos' => 'required|array|min:1',
            'asientos.*' => 'string|max:3',
            'estado' => 'nullable|string',
        ]);

        $funcion = Funcion::with('pelicula')->find($request->funcion_id);
        if (!$funcion) {
            return response()->json(['message' => 'Función no encontrada'], 404);
        }

        $user = $request->user();
        $asientosSeleccionados = $request->asientos;
        $precio = $funcion->precio;

        // Verificar que los asientos no estén ocupados
        $asientosOcupados = Reserva::where('funcion_id', $funcion->id)
            ->whereIn('numero_asiento', $asientosSeleccionados)
            ->pluck('numero_asiento')
            ->toArray();

        if (!empty($asientosOcupados)) {
            return response()->json([
                'message' => 'Los siguientes asientos no están disponibles: ' . implode(', ', $asientosOcupados)
            ], 422);
        }

        // Crear una reserva por cada asiento
        $reservasCreadas = [];
        foreach ($asientosSeleccionados as $asiento) {
            $reserva = Reserva::create([
                'user_id' => $user->id,
                'funcion_id' => $funcion->id,
                'numero_asiento' => $asiento,
                'asientos' => json_encode([$asiento]),
                'precio' => $precio,
                'estado' => $request->estado ?? 'confirmada',
            ]);
            $reservasCreadas[] = $reserva;
        }

        return response()->json([
            'data' => $reservasCreadas,
            'message' => 'Reservas creadas exitosamente'
        ], 201);
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $reserva = Reserva::with(['funcion.pelicula', 'funcion.sala', 'user'])->find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar que el usuario sea el dueño de la reserva
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }


        // Verificar que el usuario sea el dueño de la reserva
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json([
            'data' => $reserva
        ], 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:pendiente,confirmada,cancelada'
        ]);

        $user = $request->user();
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar autorización
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reserva->update($request->only('estado'));

        return response()->json([
            'data' => $reserva,
            'message' => 'Reserva actualizada'
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar autorización
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Solo permitir cancelación si está confirmada o pendiente
        if (!in_array($reserva->estado, ['pendiente', 'confirmada'])) {
            return response()->json(['message' => 'No se puede cancelar una reserva ' . $reserva->estado], 422);
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json([
            'message' => 'Reserva cancelada'
        ], 200);
    }

    /**
     * ADMIN METHODS
     */

    /**
     * Obtener todas las reservas (solo admin)
     */
    public function getAllReservas(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reservas = Reserva::with(['funcion.pelicula', 'funcion.sala', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $reservas
        ], 200);
    }

    /**
     * Aprobar una reserva (admin)
     */
    public function approveReserva(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        $reserva->update(['estado' => 'confirmada']);

        return response()->json([
            'message' => 'Reserva aprobada',
            'data' => $reserva
        ], 200);
    }

    /**
     * Rechazar una reserva (admin)
     */
    public function rejectReserva(Request $request, $id)
    {
        $request->validate([
            'razon' => 'nullable|string|max:255'
        ]);

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar autorización
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $reserva->update($request->only('estado'));

        return response()->json([
            'data' => $reserva,
            'message' => 'Reserva actualizada'
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada'], 404);
        }

        // Verificar autorización
        if ($reserva->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Solo permitir cancelación si está confirmada o pendiente
        if (!in_array($reserva->estado, ['pendiente', 'confirmada'])) {
            return response()->json(['message' => 'No se puede cancelar una reserva ' . $reserva->estado], 422);
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json([
            'message' => 'Reserva cancelada'
        ], 200);
    }
}
