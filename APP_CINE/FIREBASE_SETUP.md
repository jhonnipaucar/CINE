# Configuración de Firebase Storage

## Pasos para integrar Firebase al proyecto

### 1. Obtener las credenciales de Firebase

1. Ve a [Firebase Console](https://console.firebase.google.com/)
2. Crea un proyecto nuevo o selecciona uno existente
3. Ve a **Project Settings** (Configuración del proyecto)
4. Ve a la pestaña **Service Accounts**
5. Haz clic en **Generate a new private key**
6. Descarga el archivo JSON

### 2. Colocar las credenciales en el proyecto

1. Copia el archivo JSON descargado
2. Colócalo en la carpeta `storage/firebase/` con el nombre `credentials.json`

**Ruta final:** `storage/firebase/credentials.json`

### 3. Configurar el .env

El archivo `.env` ya tiene las variables configuradas:

```env
FIREBASE_CREDENTIALS=storage/firebase/credentials.json
FIREBASE_STORAGE_BUCKET=tu-proyecto.appspot.com
```

**Reemplaza `tu-proyecto.appspot.com` con tu bucket real** (lo encontrarás en la consola de Firebase)

### 4. Instalar dependencias de Firebase

```bash
composer require kreait/firebase-php
```

### 5. Crear la carpeta storage/firebase

```bash
mkdir -p storage/firebase
```

### 6. Probar la funcionalidad

1. Accede al panel admin en `/admin/peliculas`
2. Intenta subir una imagen a través del formulario
3. Si todo funciona, verás la imagen cargada en Firebase Storage

## Estructura del código

### FirebaseService.php
Ubicación: `app/Services/FirebaseService.php`

Métodos disponibles:
- `uploadImage($file, $folder)` - Sube una imagen a Firebase Storage
- `deleteImage($fileName)` - Elimina un archivo
- `getPublicUrl($fileName)` - Obtiene la URL pública

### PeliculaController.php
Ubicación: `app/Http/Controllers/Api/PeliculaController.php`

Endpoint nuevo:
- `POST /api/peliculas/{id}/upload-imagen` - Sube la imagen de una película

## Notas importantes

⚠️ **Seguridad:** Guarda el archivo `credentials.json` en `.gitignore` para no subirlo al repositorio

```bash
echo "storage/firebase/credentials.json" >> .gitignore
```

⚠️ **Tamaño máximo:** El máximo permitido es 5MB por imagen (configurable en `PeliculaController`)

⚠️ **Carpetas:** Las imágenes se organizan automáticamente en carpetas por tipo (ej: `peliculas/`)
