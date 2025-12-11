<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas - CINE Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-confirmada {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-cancelada {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-rechazada {
            background-color: #f5c6cb;
            color: #721c24;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal-backdrop.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎬</span>
                <h1 class="text-xl font-bold">CINE Admin - Gestión de Reservas</h1>
            </div>
            <div class="flex items-center gap-4">
                <span id="userName" class="text-sm">Admin</span>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Encabezado -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">📋 Reservas</h2>
            <p class="text-gray-600">Gestiona, aprueba y rechaza las reservas de los clientes</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                    <select id="filtroEstado" onchange="filtrarReservas()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="rechazada">Rechazada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Buscar por cliente</label>
                    <input 
                        type="text" 
                        id="buscador"
                        placeholder="Nombre o email..."
                        onkeyup="filtrarReservas()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                </div>
                <div class="flex items-end">
                    <button onclick="cargarReservas()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        🔄 Recargar
                    </button>
                </div>
            </div>
        </div>

        <!-- Mensaje de error -->
        <div id="errorMessage" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded fade-in">
            <p id="errorText"></p>
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader"></div>
            <p class="text-gray-600 mt-4">Cargando reservas...</p>
        </div>

        <!-- Tabla de reservas -->
        <div id="contenidoReservas" class="hidden fade-in">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">ID</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Cliente</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Película</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Asiento</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Precio</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Estado</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaReservas" class="divide-y divide-gray-200">
                            <!-- Se llena con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar -->
    <div id="modalRechazar" class="modal-backdrop">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Rechazar Reserva</h3>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Razón (opcional)</label>
                <textarea id="razonRechazo" placeholder="Ej: Asiento no disponible" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-600" rows="4"></textarea>
            </div>
            <div class="flex gap-3">
                <button onclick="confirmarRechazo()" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Rechazar
                </button>
                <button onclick="cerrarModal()" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para detalles -->
    <div id="modalDetalles" class="modal-backdrop">
        <div class="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Detalles de la Reserva</h3>
            <div id="detallesContent" class="space-y-3 mb-6">
                <!-- Se llena con JavaScript -->
            </div>
            <button onclick="cerrarModal()" class="w-full bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold transition">
                Cerrar
            </button>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        let reservas = [];
        let reservaSeleccionada = null;

        if (!token) {
            window.location.href = '/login';
        }

        // Verificar autenticación
        async function verificarAdmin() {
            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (response.ok) {
                    const user = await response.json();
                    document.getElementById('userName').textContent = user.name;
                    if (user.role !== 'admin') {
                        window.location.href = '/';
                    }
                } else {
                    throw new Error('No autorizado');
                }
            } catch (error) {
                window.location.href = '/login';
            }
        }

        // Cargar todas las reservas
        async function cargarReservas() {
            document.getElementById('loader').classList.remove('hidden');
            document.getElementById('contenidoReservas').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');

            try {
                const response = await fetch('/api/admin/reservas', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al cargar reservas');

                const data = await response.json();
                reservas = data.data;
                mostrarReservas(reservas);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('errorText').textContent = error.message;
                document.getElementById('errorMessage').classList.remove('hidden');
            } finally {
                document.getElementById('loader').classList.add('hidden');
            }
        }

        // Mostrar reservas en la tabla
        function mostrarReservas(reservasAMostrar) {
            const tabla = document.getElementById('tablaReservas');

            if (reservasAMostrar.length === 0) {
                tabla.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-600">No hay reservas</td></tr>';
                document.getElementById('contenidoReservas').classList.remove('hidden');
                return;
            }

            tabla.innerHTML = reservasAMostrar.map(reserva => `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-800 font-semibold">#${reserva.id}</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">${reserva.user.name}</p>
                            <p class="text-sm text-gray-600">${reserva.user.email}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-800">${reserva.funcion.pelicula.titulo}</p>
                        <p class="text-sm text-gray-600">${reserva.funcion.sala.nombre}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-800 font-semibold">${reserva.numero_asiento}</td>
                    <td class="px-6 py-4 text-gray-800 font-semibold">$${reserva.precio}</td>
                    <td class="px-6 py-4">
                        <span class="badge badge-${reserva.estado}">${reserva.estado.toUpperCase()}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="abrirDetalles(${reserva.id})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                👁️ Ver
                            </button>
                            ${reserva.estado === 'pendiente' ? `
                                <button onclick="aprobarReserva(${reserva.id})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    ✓ Aprobar
                                </button>
                                <button onclick="abrirRechazo(${reserva.id})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                    ✕ Rechazar
                                </button>
                            ` : ''}
                            <button onclick="eliminarReserva(${reserva.id})" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition">
                                🗑️ Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            document.getElementById('contenidoReservas').classList.remove('hidden');
        }

        // Filtrar reservas
        function filtrarReservas() {
            const estado = document.getElementById('filtroEstado').value.toLowerCase();
            const busqueda = document.getElementById('buscador').value.toLowerCase();

            const filtradas = reservas.filter(reserva => {
                const coincideEstado = !estado || reserva.estado === estado;
                const coincideBusqueda = !busqueda || 
                    reserva.user.name.toLowerCase().includes(busqueda) ||
                    reserva.user.email.toLowerCase().includes(busqueda);

                return coincideEstado && coincideBusqueda;
            });

            mostrarReservas(filtradas);
        }

        // Abrir modal de rechazo
        function abrirRechazo(reservaId) {
            reservaSeleccionada = reservaId;
            document.getElementById('razonRechazo').value = '';
            document.getElementById('modalRechazar').classList.add('active');
        }

        // Confirmar rechazo
        async function confirmarRechazo() {
            const razon = document.getElementById('razonRechazo').value;

            try {
                const response = await fetch(`/api/admin/reservas/${reservaSeleccionada}/rechazar`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ razon })
                });

                if (!response.ok) throw new Error('Error al rechazar reserva');

                alert('Reserva rechazada correctamente');
                cerrarModal();
                cargarReservas();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Aprobar reserva
        async function aprobarReserva(reservaId) {
            if (!confirm('¿Aprobar esta reserva?')) return;

            try {
                const response = await fetch(`/api/admin/reservas/${reservaId}/aprobar`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al aprobar reserva');

                alert('Reserva aprobada correctamente');
                cargarReservas();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Eliminar reserva
        async function eliminarReserva(reservaId) {
            if (!confirm('¿Eliminar esta reserva? Esta acción no se puede deshacer.')) return;

            try {
                const response = await fetch(`/api/admin/reservas/${reservaId}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al eliminar reserva');

                alert('Reserva eliminada correctamente');
                cargarReservas();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // Abrir modal de detalles
        function abrirDetalles(reservaId) {
            const reserva = reservas.find(r => r.id === reservaId);
            if (!reserva) return;

            const html = `
                <div class="bg-gray-50 p-4 rounded">
                    <p><strong>ID:</strong> #${reserva.id}</p>
                    <p><strong>Cliente:</strong> ${reserva.user.name} (${reserva.user.email})</p>
                    <p><strong>Película:</strong> ${reserva.funcion.pelicula.titulo}</p>
                    <p><strong>Sala:</strong> ${reserva.funcion.sala.nombre}</p>
                    <p><strong>Asiento:</strong> ${reserva.numero_asiento}</p>
                    <p><strong>Precio:</strong> $${reserva.precio}</p>
                    <p><strong>Estado:</strong> <span class="badge badge-${reserva.estado}">${reserva.estado}</span></p>
                    <p><strong>Fecha de reserva:</strong> ${new Date(reserva.created_at).toLocaleString('es-ES')}</p>
                    ${reserva.comentarios ? `<p><strong>Comentarios:</strong> ${reserva.comentarios}</p>` : ''}
                </div>
            `;

            document.getElementById('detallesContent').innerHTML = html;
            document.getElementById('modalDetalles').classList.add('active');
        }

        // Cerrar modal
        function cerrarModal() {
            document.getElementById('modalRechazar').classList.remove('active');
            document.getElementById('modalDetalles').classList.remove('active');
        }

        // Cerrar sesión
        async function handleLogout() {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` }
                });
            } catch (error) {
                console.error('Error:', error);
            } finally {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
                window.location.href = '/login';
            }
        }

        // Cargar datos al iniciar
        window.addEventListener('load', () => {
            verificarAdmin();
            cargarReservas();
        });

        // Cerrar modal al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop')) {
                cerrarModal();
            }
        });
    </script>
</body>
</html>
