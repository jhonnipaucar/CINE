<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;

class FirebaseService
{
    protected $storage;
    protected $bucketName;

    public function __construct()
    {
        try {
            $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/firebase/credentials.json'));
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception("Firebase credentials file not found at: {$credentialsPath}");
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->storage = $factory->createStorage();
            $this->bucketName = env('FIREBASE_STORAGE_BUCKET');
        } catch (\Exception $e) {
            \Log::error('Firebase initialization error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sube una imagen a Firebase Storage y devuelve la URL pública
     * 
     * @param $file Archivo a subir
     * @param string $folder Carpeta en Firebase (ej: 'peliculas', 'users')
     * @return string URL pública del archivo
     */
    public function uploadImage($file, $folder = 'peliculas')
    {
        try {
            // Generamos nombre único: peliculas/1702200000_poster.jpg
            $fileName = $folder . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            $bucket = $this->storage->getBucket($this->bucketName);

            // Subimos el archivo
            $object = $bucket->upload(
                fopen($file->getPathname(), 'r'),
                [
                    'name' => $fileName,
                    'metadata' => [
                        'cacheControl' => 'public, max-age=3600',
                    ]
                ]
            );

            // Hacemos el archivo público
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

            // Retornamos la URL pública
            return "https://storage.googleapis.com/{$this->bucketName}/{$fileName}";
        } catch (\Exception $e) {
            \Log::error('Firebase upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Elimina un archivo de Firebase
     * 
     * @param string $fileName Nombre del archivo (con ruta completa)
     * @return bool
     */
    public function deleteImage($fileName)
    {
        try {
            $bucket = $this->storage->getBucket($this->bucketName);
            $bucket->object($fileName)->delete();
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la URL pública de un archivo
     * 
     * @param string $fileName
     * @return string
     */
    public function getPublicUrl($fileName)
    {
        return "https://storage.googleapis.com/{$this->bucketName}/{$fileName}";
    }
}
