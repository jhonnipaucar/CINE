# Instrucciones para Probar el Panel de Administrador

## Pasos:

1. **Abre el navegador** y ve a: http://127.0.0.1:8000/login

2. **Inicia sesión con las siguientes credenciales:**
   - Email: `admin@cine.com`
   - Contraseña: `admin123`

3. **Después del login:**
   - Deberías ver el dashboard principal
   - En la sección de menú, deberías ver un botón **"Gestión Admin"** con icono ⚙️
   - Haz clic en ese botón para ir al panel administrativo

4. **En el panel de administración** podrás:
   - Gestionar películas (crear, editar, eliminar)
   - Gestionar géneros (crear, editar, eliminar)
   - Gestionar salas (crear, editar, eliminar)
   - Gestionar funciones/horarios (crear, editar, eliminar)

## Si algo no funciona:

### El botón "Gestión Admin" no aparece:
- Abre la consola del navegador (F12)
- Ve a la pestaña "Application" → "Local Storage"
- Verifica que exista la clave `user_data` con un JSON que contenga `"role": "admin"`
- Si no está, intenta hacer logout y login nuevamente

### No puedo acceder a /admin:
- Verifica que el servidor esté corriendo: http://127.0.0.1:8000
- Abre la consola (F12) y busca mensajes de error en la red
- Intenta ir directamente a: http://127.0.0.1:8000/admin (deberías ver un mensaje pidiendo login)

### El panel de admin se carga pero dice "Cargando...":
- Abre la consola (F12) → pestaña "Network"
- Verifica que las peticiones a http://127.0.0.1:8000/api/ tengan status 200
- Si hay errores 401 o 403, significa que el token no se está enviando correctamente
- Verifica que `auth_token` esté presente en localStorage

## Información Técnica:

- **Login API:** POST /api/auth/login
- **Token storage:** localStorage.auth_token
- **User data:** localStorage.user_data
- **Admin routes:** /admin, /admin/peliculas, /admin/generos, /admin/salas, /admin/funciones
- **API endpoints:** /api/generos, /api/salas, /api/peliculas, /api/funciones
