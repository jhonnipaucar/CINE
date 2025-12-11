# 🎬 Firebase Storage Integration - CINE App

## 📚 Documentación Completa

Este documento explica cómo traer imágenes propias de las películas mediante **Firebase Storage** en lugar de usar solo las imágenes de la API.

---

## 🎯 Objetivos

- ✅ Subir imágenes de películas a Firebase Storage
- ✅ Almacenarlas en la nube (no en el servidor)
- ✅ Obtener URLs permanentes y públicas
- ✅ Mostrarlas en el catálogo de películas
- ✅ Mantener compatibilidad con imágenes de TMDB

---

## 🔧 Configuración Inicial

### 1. Crear Proyecto Firebase

1. Ir a **[console.firebase.google.com](https://console.firebase.google.com)**
2. Click en **"Crear proyecto"**
3. Nombrar proyecto (ej: "CINE-App")
4. Aceptar términos y crear

### 2. Crear Storage Bucket

1. En tu proyecto Firebase → **Build** → **Storage**
2. Click en **"Comenzar"** o **"Crear bucket"**
3. Elegir ubicación (ej: `europe-west1`)
4. Seleccionar **"Comenzar en modo de prueba"** (para desarrollo)
5. Click en **"Crear"**

### 3. Obtener Credenciales

**Método 1: Desde Project Settings**
1. Engranaje (⚙️) → **Project Settings**
2. Pestaña **"Service Accounts"**
3. Language selector → **PHP**
4. Click **"Generate New Private Key"**
5. Se descarga un JSON

**Método 2: Desde API Key**
1. Engranaje (⚙️) → **Project Settings**
2. Ir a **"General"**
3. Copiar datos que aparecen en la página

### 4. Agregar al .env

En `APP_CINE/.env`:

```env
# Firebase Configuration
FIREBASE_API_KEY=AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
FIREBASE_AUTH_DOMAIN=tuproyecto.firebaseapp.com
FIREBASE_PROJECT_ID=tuproyecto-xxxxx
FIREBASE_STORAGE_BUCKET=tuproyecto.appspot.com
FIREBASE_MESSAGING_SENDER_ID=123456789012
FIREBASE_APP_ID=1:123456789012:web:abcd1234efgh5678
FIREBASE_CLIENT_EMAIL=firebase-adminsdk-xxxxx@tuproyecto.iam.gserviceaccount.com
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
```

### 5. Configurar Reglas de Storage

En **Firebase Console** → **Storage** → **Rules**:

```javascript
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    // Permitir lectura pública de imágenes de películas
    match /peliculas/{allPaths=**} {
      allow read: if true;
      allow write: if false;  // Solo el servidor puede escribir
    }
    
    // Permitir lectura de portadas
    match /portadas/{allPaths=**} {
      allow read: if true;
      allow write: if false;
    }
  }
}
```

Luego click en **"Publicar"**.

---

## 🚀 Cómo Usar

### Opción 1: Subir desde API (Recomendado)

#### Endpoint:
```
POST /api/peliculas/{id}/upload-imagen
```

#### Headers:
```
Authorization: Bearer {token_autenticacion}
Content-Type: multipart/form-data
```

#### Body:
```
imagen: (archivo de imagen)
```

#### Ejemplo con cURL:
```bash
curl -X POST "http://127.0.0.1:8000/api/peliculas/1/upload-imagen" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -F "imagen=@/ruta/a/imagen.jpg"
```

#### Respuesta Exitosa:
```json
{
  "message": "Imagen subida correctamente",
  "data": {
    "url": "https://storage.googleapis.com/cine-app.appspot.com/peliculas/1733286456_imagen.jpg",
    "pelicula": {
      "id": 1,
      "titulo": "Mi Película",
      "url_imagen": "https://storage.googleapis.com/...",
      "image_url": "https://storage.googleapis.com/..."
    }
  }
}
```

### Opción 2: Subir desde Formulario Web

#### Acceso:
```
http://127.0.0.1:8000/upload-pelicula
```

#### Características:
- ✅ Interfaz amigable
- ✅ Vista previa de imagen
- ✅ Drag & drop support
- ✅ Validación en tiempo real
- ✅ Mensajes de éxito/error

#### Pasos:
1. Ingresar ID de película
2. Seleccionar imagen (o arrastrar)
3. Click en "Subir Imagen"
4. Esperar confirmación

---

## 📱 Usar en Frontend

### Mostrar Imagen en HTML:

```html
<img src="{{ $pelicula->image_url }}" 
     alt="{{ $pelicula->titulo }}"
     loading="lazy">
```

### Desde JavaScript/Fetch:

```javascript
// Obtener películas
const response = await fetch('http://127.0.0.1:8000/api/peliculas');
const data = await response.json();

// Mostrar en UI
data.data.forEach(pelicula => {
    const img = document.createElement('img');
    img.src = pelicula.image_url;  // Firebase URL automáticamente
    img.alt = pelicula.titulo;
    document.body.appendChild(img);
});
```

### Con Laravel Blade:

```blade
@foreach($peliculas as $pelicula)
    <div class="pelicula">
        <img src="{{ $pelicula->image_url }}" alt="{{ $pelicula->titulo }}">
        <h3>{{ $pelicula->titulo }}</h3>
        <p>{{ $pelicula->sinopsis }}</p>
    </div>
@endforeach
```

---

## 🔑 Prioridad de URLs de Imagen

El modelo `Pelicula` intenta obtener imagen en este orden:

1. **Firebase Storage** (si existe y es URL válida)
   - Formato: `https://storage.googleapis.com/...`
2. **TMDB Poster** (si existe)
   - Formato: `https://image.tmdb.org/...`
3. **URL Imagen Fallback**
   - Campo `url_imagen` del modelo

---

## ✅ Validaciones

- **Tipos permitidos**: JPEG, PNG, GIF, WebP
- **Tamaño máximo**: 5 MB
- **Requiere**: Autenticación como admin
- **Carpeta**: Se guarda en `/peliculas/` de Firebase

---

## 🛡️ Seguridad

- ✅ Solo administradores pueden subir (`role === 'admin'`)
- ✅ Validación en cliente y servidor
- ✅ Archivos públicos de lectura (pero indexados solo por tu app)
- ✅ Imágenes resguardadas en Firebase (no en tu servidor)
- ✅ URLs permanentes con CDN de Google

---

## 🔄 Arquitectura del Flujo

```
Usuario Admin
    ↓
[Formulario web o API call]
    ↓
PeliculaController::uploadImage()
    ↓
Validar:
- Autenticación (Bearer token)
- Rol admin
- Archivo válido (<5MB, es imagen)
    ↓
FirebaseStorageService::uploadImage()
    ↓
API REST Firebase Storage
    ↓
Google Cloud Storage
    ↓
URL pública generada
    ↓
Guardar en BD (url_imagen)
    ↓
Retornar URL al cliente
    ↓
Mostrar en UI
```

---

## 📊 Estructura de Archivos

```
app/
├── Services/
│   └── FirebaseStorageService.php      ← Sube a Firebase
├── Http/Controllers/Api/
│   └── PeliculaController.php          ← Maneja uploads
├── Models/
│   └── Pelicula.php                    ← image_url attribute
└── config/
    └── firebase.php                    ← Configuración

resources/views/
├── upload-pelicula.blade.php           ← Formulario web
└── EJEMPLO_MOSTRAR_IMAGENES.blade.php  ← Cómo mostrar

routes/
├── api.php                             ← Endpoint POST
└── web.php                             ← Ruta web GET

.env                                    ← Credenciales Firebase
FIREBASE_SETUP.md                       ← Guía detallada
RESUMEN_FIREBASE.md                     ← Resumen rápido
```

---

## 🐛 Solución de Problemas

### Error: "Invalid API Key"
```
Solución: Verifica que FIREBASE_API_KEY en .env sea correcta
```

### Error: "Permission denied"
```
Solución: Revisa las reglas en Storage → Rules en Firebase Console
Deben permitir lectura pública:
  allow read: if true;
```

### Error: "Bucket not found"
```
Solución: El FIREBASE_STORAGE_BUCKET debe incluir .appspot.com
Correcto: tuproyecto.appspot.com
Incorrecto: tuproyecto
```

### La imagen se sube pero no aparece
```
Solución: Espera 10-20 segundos (caché del CDN de Google)
O: Actualiza la página (F5)
```

### Error: "Unauthorized" en POST
```
Solución: El usuario debe ser admin (role === 'admin')
O: Token de autenticación expirado o inválido
```

---

## 📈 Próximos Pasos

- [ ] Configurar credenciales Firebase en `.env`
- [ ] Crear Storage bucket en Firebase
- [ ] Actualizar reglas de Storage
- [ ] Probar upload: `http://127.0.0.1:8000/upload-pelicula`
- [ ] Verificar que aparezca en API: `/api/peliculas/1`
- [ ] Integrar en dashboard/catalogo
- [ ] Implementar edición/eliminación de imágenes
- [ ] Respaldar imágenes regularmente

---

## 📞 Soporte

Si tienes problemas:

1. Revisa los logs: `tail -f storage/logs/laravel.log`
2. Abre DevTools (F12) en el navegador
3. Verifica credenciales en `.env`
4. Confirma que Firebase Storage esté habilitado
5. Revisa las reglas de Storage

---

## 🎉 ¡Listo!

Ya tienes Firebase Storage integrado. Ahora puedes:
- ✅ Subir imágenes de películas propias
- ✅ Mostrarlas en tu catálogo
- ✅ Combinarlas con imágenes de TMDB
- ✅ Almacenarlas en la nube de forma segura

**¿Necesitas ayuda?** Revisa FIREBASE_SETUP.md para más detalles técnicos.

