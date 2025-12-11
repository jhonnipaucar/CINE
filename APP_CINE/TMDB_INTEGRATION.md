# Integración TMDb - API de Películas

Esta aplicación ha sido enriquecida con datos de **The Movie Database (TMDb)**, una API gratuita que proporciona información detallada de películas.

## 📋 Descripción de la Integración

### Componentes Implementados

1. **TMDbService.php** (`app/Services/TMDbService.php`)
   - Servicio centralizado para consumir la API de TMDb
   - Métodos disponibles:
     - `searchMovie($title)` - Buscar películas por título
     - `getMovieDetails($tmdbId)` - Obtener detalles completos
     - `getPopularMovies($page)` - Películas populares
     - `getUpcomingMovies($page)` - Películas próximas
     - `formatMovieResponse($data)` - Formatear respuestas
   - Cache automático de 7-30 días para optimizar

2. **TMDbController.php** (`app/Http/Controllers/Api/TMDbController.php`)
   - Controlador API REST para TMDb
   - Rutas públicas sin autenticación
   - Soporte para paginación y búsqueda

3. **Rutas API** (`routes/api.php`)
   - `GET /api/tmdb/search?query=...` - Buscar películas
   - `GET /api/tmdb/popular` - Películas populares
   - `GET /api/tmdb/upcoming` - Películas próximas
   - `GET /api/tmdb/movie/{id}` - Detalle de película
   - `POST /api/tmdb/peliculas/{id}/enrich` - Enriquecer película local

4. **Campos de Base de Datos**
   ```php
   - tmdb_id (integer, único)
   - calificacion_tmdb (decimal 3.1)
   - votos_tmdb (integer)
   - descripcion (text)
   ```

5. **Admin Panel UI**
   - Búsqueda en vivo en TMDb
   - Botón "Populares" para cargar películas populares
   - Importación rápida de películas con datos pre-llenados
   - Vista previa de imágenes (posters)
   - Calificaciones mostradas en tiempo real

6. **Catálogo Público**
   - Muestra calificación TMDb con emoji ⭐
   - Cuenta de votos
   - Modal de detalle enriquecido
   - Imágenes de TMDb como fallback

## 🚀 Configuración

### 1. Obtener API Key (Gratis)

1. Ir a: https://www.themoviedb.org/settings/api
2. Crear cuenta gratuita si no tienes
3. Solicitar API Key
4. Copiar la clave

### 2. Agregar Variables de Entorno

Editar `.env`:

```env
TMDB_API_KEY=tu_clave_aqui
TMDB_API_URL=https://api.themoviedb.org/3
TMDB_IMAGE_BASE_URL=https://image.tmdb.org/t/p
```

### 3. Ejecutar Migraciones

```bash
php artisan migrate
```

Esto agregará los campos TMDb a la tabla `peliculas`.

## 💻 Uso

### Desde la Interfaz Admin

1. **Búsqueda Manual**
   ```
   - Ir a Panel Admin > Gestión de Películas
   - Ingresar título en "Buscar en TMDb"
   - Click en "🔍 Buscar"
   - Seleccionar película y click "➕ Importar"
   - Completar datos faltantes y guardar
   ```

2. **Cargar Populares**
   ```
   - Click en "⭐ Populares"
   - Se mostrarán las 20 películas más populares
   - Importar las deseadas
   ```

### Desde Terminal (Artisan)

**Enriquecer películas existentes:**
```bash
php artisan tmdb:import
```

**Importar películas populares:**
```bash
php artisan tmdb:import --popular --limit=20
```

### Desde Código (Tinker)

```bash
php artisan tinker
```

Luego en el REPL de Tinker:

```php
// Buscar película
$tmdb = app(App\Services\TMDbService::class);
$resultados = $tmdb->searchMovie("Inception");

// Obtener detalles
$detalles = $tmdb->getMovieDetails(27205);
$formateado = $tmdb->formatMovieResponse($detalles);

// Actualizar película local
$pelicula = App\Models\Pelicula::find(1);
$pelicula->update([
    'tmdb_id' => $formateado['id'],
    'calificacion_tmdb' => $formateado['calificacion'],
    'votos_tmdb' => $formateado['votos'],
    'descripcion' => $formateado['sinopsis']
]);
```

### Desde API REST

```bash
# Buscar
curl "http://localhost:8000/api/tmdb/search?query=The%20Matrix"

# Populares
curl "http://localhost:8000/api/tmdb/popular"

# Detalles específicos
curl "http://localhost:8000/api/tmdb/movie/603"

# Enriquecer película
curl -X POST \
  http://localhost:8000/api/tmdb/peliculas/1/enrich \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"tmdb_id": 603}'
```

## 📊 Respuesta Formateada

TMDb retorna datos enriquecidos:

```json
{
  "id": 603,
  "titulo": "The Matrix",
  "sinopsis": "Un hacker descubre...",
  "calificacion": 8.7,
  "votos": 24000,
  "fecha_lanzamiento": "1999-03-31",
  "poster": "https://image.tmdb.org/t/p/w500/...",
  "portada": "https://image.tmdb.org/t/p/w1280/...",
  "duracion": 136,
  "generos": [...],
  "idioma": "en",
  "presupuesto": 63000000,
  "ingresos": 467222728
}
```

## ⚙️ Configuración Avanzada

### Rate Limiting

TMDb permite 40 requests/10 segundos en free tier. El servicio implementa:
- **Cache automático** (7-30 días según endpoint)
- **Delay de 1 segundo** en comandos Artisan
- **Validación** de API key antes de realizar requests

### Campos Ignorados

Algunos campos de TMDb no se almacenan para optimizar:
- Video clips
- Trailers (disponibles en API pero no en DB)
- Reseñas detalladas
- Cast completo (disponible pero no almacenado)

Para obtener estos datos, consulta la API directamente:
```php
$detalles = $tmdb->getMovieDetails($id);
// Incluye 'credits' y 'reviews' por defecto
```

### Personalización

Editar `TMDbService.php` para:
- Cambiar idioma (buscar `language` => `es-ES`)
- Agregar más datos a la respuesta
- Cambiar tamaños de imágenes (w500, w1280, etc.)

## 🔒 Seguridad

- API Key almacenada en `.env`
- No se expone en respuestas públicas
- Rutas TMDb son públicas (sin autenticación requerida)
- Enriquecimiento de películas requiere autenticación admin

## 📈 Estadísticas Disponibles

Cada película puede mostrar:
- ⭐ Calificación (0-10)
- 🗳️ Número de votos
- 📅 Fecha de lanzamiento
- ⏱️ Duración en minutos
- 🎬 Género(s)
- 🖼️ Poster y backdrop (imágenes)
- 💰 Presupuesto e ingresos (si disponible)
- 🌍 Idioma original

## 🐛 Troubleshooting

**"TMDB API key no configurada"**
- Verifica que `TMDB_API_KEY` esté en `.env`
- Reinicia el servidor: `php artisan serve`

**Imágenes no se cargan**
- Las URLs de imagen de TMDb son públicas
- Verifica que tu conexión permita acceso a `image.tmdb.org`
- Como fallback, la app muestra emoji 🎬

**Rate limit excedido**
- Espera 10 segundos e intenta de nuevo
- Usa búsquedas más específicas
- El cache evita requests repetidos

**Sin resultados en búsqueda**
- TMDb solo devuelve películas exactas/cercanas
- Intenta con títulos en inglés
- Busca por año también: "Inception 2010"

## 📚 Recursos Útiles

- **Docs TMDb**: https://developers.themoviedb.org/3
- **API Reference**: https://developers.themoviedb.org/3/movies
- **Image URLs**: https://developers.themoviedb.org/3/getting-started/images
- **Status Codes**: https://developers.themoviedb.org/3/getting-started/status-codes

## 🎯 Próximas Mejoras

- [ ] Sincronización automática de calificaciones TMDb
- [ ] Caché Redis para mejor rendimiento
- [ ] Soporte para reviews de TMDb
- [ ] Importación masiva de películas por género
- [ ] Webhooks para actualizar cuando TMDb cambia calificaciones

---

**Versión**: 1.0  
**Última actualización**: Diciembre 2025  
**API TMDb Versión**: 3
