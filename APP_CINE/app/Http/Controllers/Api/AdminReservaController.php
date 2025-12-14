<?php

namespace App\Http\Controllers\Api;

use App\Models\Reserva;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminReservaController extends Controller
{
    // Obtener todas las reservas (solo para admin)
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user || $user->role !== 'admin') {
                return response()->json(['message' => 'No tienes permiso'], 403);
            }
            
            // Cargar todas las reservas con relaciones
            $reservas = Reserva::get()
                ->map(function($reserva) {
                    // Cargar usuario
                    if ($reserva->user_id) {
                        $reserva->user = \App\Models\User::find($reserva->user_id);
                    }
                    
                    // Cargar función de forma segura
                    if ($reserva->funcion_id) {
                        $funcion = Funcion::find($reserva->funcion_id);
                        if ($funcion) {
                            $reserva->funcion = $funcion;
                            // Cargar película y sala de la función
                            if ($funcion->pelicula_id) {
                                $reserva->funcion->pelicula = Pelicula::find($funcion->pelicula_id);
                            }
                            if ($funcion->sala_id) {
                                $reserva->funcion->sala = Sala::find($funcion->sala_id);
                            }
                        }
                    }
                    return $reserva;
                });

            return response()->json([
                'data' => $reservas
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error al cargar reservas: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Actualizar estado de una reserva (solo para admin)
    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            if (!$user || $user->role !== 'admin') {
                return response()->json(['message' => 'No tienes permiso'], 403);
            }
            
            $request->validate([
                'estado' => 'required|in:pendiente,confirmada,rechazada,cancelada'
            ]);
            
            $reserva = Reserva::find($id);
            if (!$reserva) {
                return response()->json(['message' => 'Reserva no encontrada'], 404);
            }
            
            $reserva->update(['estado' => $request->estado]);
            
            return response()->json([
                'message' => 'Reserva actualizada correctamente',
                'data' => $reserva
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar reserva: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Eliminar una reserva (solo para admin)
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            
            if (!$user || $user->role !== 'admin') {
                return response()->json(['message' => 'No tienes permiso'], 403);
            }
            
            $reserva = Reserva::find($id);
            if (!$reserva) {
                return response()->json(['message' => 'Reserva no encontrada'], 404);
            }
            
            $reserva->delete();
            
            return response()->json([
                'message' => 'Reserva eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar reserva: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
