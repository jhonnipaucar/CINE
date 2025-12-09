<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FirebaseStorageService
{
    /**
     * Subir archivo a Firebase Storage
     * Como alternativa, si Firebase SDK no está disponible,
     * usaremos almacenamiento local simulado
     */
    public static function uploadImage($file, $path = 'peliculas')
    {
        try {
            // Validar archivo
            if (!$file->isValid()) {
                throw new \Exception('Archivo inválido');
            }

            // Generar nombre único
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Guardar en storage/public/peliculas
            $storagePath = Storage::disk('public')->putFileAs(
                $path,
                $file,
                $filename
            );

            // Retornar URL pública
            $url = asset('storage/' . $storagePath);

            return [
                'success' => true,
                'url' => $url,
                'path' => $storagePath
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar archivo de Firebase Storage
     */
    public static function deleteImage($path)
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtener URL pública de archivo
     */
    public static function getUrl($path)
    {
        return asset('storage/' . $path);
    }
}
