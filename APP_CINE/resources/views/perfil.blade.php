<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - CINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .navbar a.active {
            background-color: rgba(255, 255, 255, 0.3);
            font-weight: bold;
        }

        .perfil-card {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .perfil-header {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid #eee;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            flex-shrink: 0;
        }

        .perfil-datos {
            flex: 1;
        }

        .perfil-nombre {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .perfil-role {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .perfil-role.admin {
            background: #f59e0b;
        }

        .perfil-info-texto {
            color: #666;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .perfil-info-label {
            font-weight: bold;
            color: #333;
        }

        .form-grupo {
            margin-bottom: 24px;
        }

        .form-grupo label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .form-grupo input,
        .form-grupo textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-grupo textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-grupo input:focus,
        .form-grupo textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-grupo input:disabled {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }

        .seccion {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .seccion-titulo {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .estadistica {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 16px;
        }

        .estadistica-numero {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }

        .estadistica-texto {
            color: #666;
            font-size: 14px;
            margin-top: 4px;
        }

        .botones-grupo {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-primario {
            background-color: #667eea;
            color: white;
        }

        .btn-primario:hover {
            background-color: #5568d3;
        }

        .btn-secundario {
            background-color: #ddd;
            color: #333;
        }

        .btn-secundario:hover {
            background-color: #ccc;
        }

        .btn-peligro {
            background-color: #ef4444;
            color: white;
        }

        .btn-peligro:hover {
            background-color: #dc2626;
        }

        .mensaje {
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            display: none;
        }

        .mensaje.exito {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
            display: block;
        }

        .mensaje.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            display: block;
        }

        .historial-item {
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 12px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
        }

        .historial-pelicula {
            font-weight: bold;
            color: #333;
        }

        .historial-fecha {
            color: #666;
            font-size: 12px;
        }

        .historial-estado {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .estado-confirmado {
            background: #d1fae5;
            color: #065f46;
        }

        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .estado-cancelado {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">🎬 CINE App</h1>
            </div>
            <div class="flex gap-2 items-center">
                <a href="/peliculas">Películas</a>
                <a href="/generos">Géneros</a>
                <a href="/funciones">Funciones</a>
                <a href="/salas">Salas</a>
                <a href="/reservas">Mis Reservas</a>
                <a href="/perfil" class="active">Mi Perfil</a>
                <a href="/admin/peliculas" id="adminLink" style="display:none;">Gestión Admin</a>
                <a href="#" onclick="cerrarSesion()">Salir</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto p-4">
        <!-- Mensajes -->
        <div id="mensajeExito" class="mensaje exito">✅ Perfil actualizado correctamente</div>
        <div id="mensajeError" class="mensaje error">❌ Error al actualizar el perfil</div>

        <!-- Perfil Header -->
        <div class="perfil-card fade-in">
            <div class="perfil-header">
                <div class="avatar" id="avatar">👤</div>
                <div class="perfil-datos">
                    <div class="perfil-nombre" id="nombreCompleto">Cargando...</div>
                    <div class="perfil-role" id="userRole">Usuario</div>
                    <div class="perfil-info-texto">
                        <span class="perfil-info-label">Email:</span>
                        <span id="userEmail">-</span>
                    </div>
                    <div class="perfil-info-texto">
                        <span class="perfil-info-label">Miembro desde:</span>
                        <span id="userFecha">-</span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
                <div class="estadistica">
                    <div class="estadistica-numero" id="totalReservas">0</div>
                    <div class="estadistica-texto">Reservas realizadas</div>
                </div>
                <div class="estadistica">
                    <div class="estadistica-numero" id="reservasConfirmadas">0</div>
                    <div class="estadistica-texto">Confirmadas</div>
                </div>
                <div class="estadistica">
                    <div class="estadistica-numero" id="asientosReservados">0</div>
                    <div class="estadistica-texto">Asientos reservados</div>
                </div>
            </div>
        </div>

        <!-- Sección: Datos Personales -->
        <div class="seccion">
            <div class="seccion-titulo">👤 Datos Personales</div>
            
            <div class="form-grupo">
                <label>Nombre completo</label>
                <input type="text" id="nombre" placeholder="Tu nombre completo">
            </div>

            <div class="form-grupo">
                <label>Email</label>
                <input type="email" id="email" disabled placeholder="Tu email">
            </div>

            <div class="form-grupo">
                <label>Teléfono</label>
                <input type="tel" id="telefono" placeholder="Tu número de teléfono (opcional)">
            </div>

            <div class="form-grupo">
                <label>Biografía</label>
                <textarea id="biografia" placeholder="Cuéntanos sobre ti (opcional)"></textarea>
            </div>

            <div class="botones-grupo">
                <button class="btn btn-primario" onclick="actualizarPerfil()">💾 Guardar cambios</button>
                <button class="btn btn-secundario" onclick="cancelarEdicion()">❌ Cancelar</button>
            </div>
        </div>

        <!-- Sección: Historial de Reservas -->
        <div class="seccion">
            <div class="seccion-titulo">📜 Historial de Reservas</div>
            <div id="historialReservas">
                <p class="text-gray-600">Cargando historial...</p>
            </div>
        </div>

        <!-- Sección: Preferencias de Seguridad -->
        <div class="seccion">
            <div class="seccion-titulo">🔒 Seguridad</div>
            
            <p class="text-gray-600 mb-4">Gestiona tu contraseña y seguridad de la cuenta</p>

            <div class="form-grupo">
                <label>Contraseña actual</label>
                <input type="password" id="contraseniaActual" placeholder="Tu contraseña actual">
            </div>

            <div class="form-grupo">
                <label>Nueva contraseña</label>
                <input type="password" id="contraseniaNew" placeholder="Tu nueva contraseña">
            </div>

            <div class="form-grupo">
                <label>Confirmar nueva contraseña</label>
                <input type="password" id="contraseniaConfirm" placeholder="Confirma tu nueva contraseña">
            </div>

            <div class="botones-grupo">
                <button class="btn btn-primario" onclick="cambiarContrasenia()">🔐 Cambiar contraseña</button>
            </div>
        </div>

        <!-- Sección: Zona de Peligro -->
        <div class="seccion" style="border-left: 4px solid #ef4444;">
            <div class="seccion-titulo">⚠️ Zona de Peligro</div>
            
            <p class="text-gray-600 mb-4">Acciones que no se pueden deshacer</p>

            <button class="btn btn-peligro" onclick="confirmarEliminacion()">
                🗑️ Eliminar mi cuenta permanentemente
            </button>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        let userData = null;

        if (!token) {
            window.location.href = '/login';
        }

        // Cargar datos del usuario
        async function cargarPerfil() {
            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar perfil');
                
                userData = await response.json();
                mostrarPerfil(userData);
                cargarHistorialReservas();
                verificarAdmin();
            } catch (error) {
                console.error('Error:', error);
                window.location.href = '/login';
            }
        }

        function mostrarPerfil(user) {
            document.getElementById('nombreCompleto').textContent = user.name || 'Usuario';
            document.getElementById('userEmail').textContent = user.email;
            document.getElementById('userRole').textContent = user.role === 'admin' ? '👑 Administrador' : '👤 Usuario';
            if (user.role === 'admin') {
                document.getElementById('userRole').classList.add('admin');
                document.getElementById('adminLink').style.display = 'inline-block';
            }
            
            const fecha = new Date(user.created_at).toLocaleDateString('es-ES');
            document.getElementById('userFecha').textContent = fecha;

            // Llenar formulario
            document.getElementById('nombre').value = user.name || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('telefono').value = user.phone || '';
            document.getElementById('biografia').value = user.bio || '';

            // Avatar con inicial
            const inicial = user.name ? user.name.charAt(0).toUpperCase() : 'U';
            document.getElementById('avatar').textContent = inicial;
        }

        async function cargarHistorialReservas() {
            try {
                const response = await fetch('/api/reservas?user_id=' + userData.id, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error');
                
                const reservas = await response.json();
                mostrarHistorial(reservas);
                
                // Actualizar estadísticas
                document.getElementById('totalReservas').textContent = reservas.length;
                const confirmadas = reservas.filter(r => r.estado === 'confirmado').length;
                document.getElementById('reservasConfirmadas').textContent = confirmadas;
                document.getElementById('asientosReservados').textContent = 
                    reservas.reduce((sum, r) => sum + (r.asientos?.length || 1), 0);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function mostrarHistorial(reservas) {
            const container = document.getElementById('historialReservas');
            
            if (reservas.length === 0) {
                container.innerHTML = '<p class="text-gray-600">No tienes reservas aún</p>';
                return;
            }

            container.innerHTML = reservas.map(reserva => {
                const fecha = new Date(reserva.created_at).toLocaleDateString('es-ES');
                const estado = reserva.estado || 'pendiente';
                
                return `
                    <div class="historial-item">
                        <div>
                            <div class="historial-pelicula">${reserva.funcion?.pelicula?.titulo || 'Película'}</div>
                            <div class="historial-fecha">Reservada el ${fecha}</div>
                        </div>
                        <div class="historial-estado estado-${estado}">
                            ${estado === 'confirmado' ? '✅ Confirmada' : estado === 'cancelado' ? '❌ Cancelada' : '⏳ Pendiente'}
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function actualizarPerfil() {
            const nombre = document.getElementById('nombre').value;
            const telefono = document.getElementById('telefono').value;
            const biografia = document.getElementById('biografia').value;

            if (!nombre) {
                mostrarError('El nombre es requerido');
                return;
            }

            try {
                const response = await fetch('/api/user', {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: nombre,
                        phone: telefono,
                        bio: biografia
                    })
                });

                if (!response.ok) throw new Error('Error');

                mostrarExito('Perfil actualizado correctamente');
                cargarPerfil();
            } catch (error) {
                console.error('Error:', error);
                mostrarError('Error al actualizar el perfil');
            }
        }

        async function cambiarContrasenia() {
            const actual = document.getElementById('contraseniaActual').value;
            const nueva = document.getElementById('contraseniaNew').value;
            const confirmar = document.getElementById('contraseniaConfirm').value;

            if (!actual || !nueva || !confirmar) {
                mostrarError('Completa todos los campos');
                return;
            }

            if (nueva !== confirmar) {
                mostrarError('Las contraseñas no coinciden');
                return;
            }

            if (nueva.length < 8) {
                mostrarError('La contraseña debe tener al menos 8 caracteres');
                return;
            }

            try {
                const response = await fetch('/api/change-password', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: actual,
                        password: nueva,
                        password_confirmation: confirmar
                    })
                });

                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Contraseña actual incorrecta');
                    }
                    throw new Error('Error');
                }

                mostrarExito('Contraseña actualizada correctamente');
                document.getElementById('contraseniaActual').value = '';
                document.getElementById('contraseniaNew').value = '';
                document.getElementById('contraseniaConfirm').value = '';
            } catch (error) {
                console.error('Error:', error);
                mostrarError(error.message || 'Error al cambiar la contraseña');
            }
        }

        function confirmarEliminacion() {
            if (confirm('⚠️ ¿Estás seguro? Esta acción eliminará tu cuenta y todos tus datos permanentemente.\n\nEsta acción NO se puede deshacer.')) {
                eliminarCuenta();
            }
        }

        async function eliminarCuenta() {
            try {
                const response = await fetch('/api/user', {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error');

                mostrarExito('Cuenta eliminada. Redirigiendo...');
                setTimeout(() => {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = '/login';
                }, 2000);
            } catch (error) {
                console.error('Error:', error);
                mostrarError('Error al eliminar la cuenta');
            }
        }

        function cancelarEdicion() {
            cargarPerfil();
        }

        function mostrarExito(mensaje) {
            const el = document.getElementById('mensajeExito');
            el.textContent = '✅ ' + mensaje;
            el.style.display = 'block';
            setTimeout(() => {
                el.style.display = 'none';
            }, 5000);
        }

        function mostrarError(mensaje) {
            const el = document.getElementById('mensajeError');
            el.textContent = '❌ ' + mensaje;
            el.style.display = 'block';
            setTimeout(() => {
                el.style.display = 'none';
            }, 5000);
        }

        function verificarAdmin() {
            if (userData.role === 'admin') {
                document.getElementById('adminLink').style.display = 'inline-block';
            }
        }

        function cerrarSesion() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }

        // Cargar datos al iniciar
        window.addEventListener('load', () => {
            cargarPerfil();
        });
    </script>
</body>
</html>
