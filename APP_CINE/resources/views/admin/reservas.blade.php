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

        .estado-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .estado-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .estado-confirmada {
            background-color: #dcfce7;
            color: #166534;
        }

        .estado-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .estado-cancelada {
            background-color: #e5e7eb;
            color: #374151;
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
                <a href="/admin" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition">Volver</a>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Reservas de Clientes</h2>
            <p class="text-gray-600 mt-2">Gestiona las reservas realizadas por los clientes</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Filtrar por Estado</label>
                    <select id="filtroEstado" onchange="filtrarReservas()" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="rechazada">Rechazada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Buscar por Cliente</label>
                    <input type="text" id="buscarCliente" onkeyup="filtrarReservas()" placeholder="Nombre o email..." class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                </div>
                <div class="flex items-end">
                    <button onclick="limpiarFiltros()" class="w-full bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition">
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Reservas -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div id="loaderReservas" class="text-center py-12">
                <div class="loader mx-auto"></div>
                <p class="text-gray-600 mt-4">Cargando reservas...</p>
            </div>

            <div id="contenidoReservas" class="hidden overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Cliente</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Película</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Fecha/Hora</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Sala</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Asientos</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Estado</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-800">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                    </tbody>
                </table>
            </div>

            <div id="noReservas" class="hidden text-center py-12">
                <p class="text-gray-500 text-lg">No se encontraron reservas</p>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div id="modalDetalles" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="bg-gray-100 border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Detalles de la Reserva</h3>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div id="detallesContenido" class="p-6 space-y-4">
                <!-- Se llenará con JavaScript -->
            </div>

            <div class="bg-gray-100 border-t px-6 py-4 flex gap-2 justify-end">
                <button onclick="cerrarModal()" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        const API_URL = 'http://127.0.0.1:8000/api';
        let token = localStorage.getItem('auth_token');
        let reservasOriginales = [];
        let reservasFiltradas = [];

        document.addEventListener('DOMContentLoaded', () => {
            // Verificar autenticación
            const userData = localStorage.getItem('user_data');
            if (!userData) {
                window.location.href = '/';
                return;
            }

            cargarReservas();
        });

        async function cargarReservas() {
            try {
                const response = await fetch(`${API_URL}/admin/reservas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar reservas');
                }

                const data = await response.json();
                reservasOriginales = data.data || data;
                reservasFiltradas = reservasOriginales;
                mostrarReservas(reservasOriginales);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loaderReservas').innerHTML = '<p class="text-red-500">Error al cargar reservas</p>';
            }
        }

        function mostrarReservas(reservas) {
            const tbody = document.getElementById('tablaBody');
            tbody.innerHTML = '';

            if (reservas.length === 0) {
                document.getElementById('noReservas').classList.remove('hidden');
                document.getElementById('contenidoReservas').classList.add('hidden');
                document.getElementById('loaderReservas').classList.add('hidden');
                return;
            }

            document.getElementById('noReservas').classList.add('hidden');
            document.getElementById('loaderReservas').classList.add('hidden');
            document.getElementById('contenidoReservas').classList.remove('hidden');

            reservas.forEach(reserva => {
                // Parsear fecha
                let fechaFormato = 'N/A';
                let horaFormato = 'N/A';
                try {
                    const fechaObj = new Date(reserva.funcion?.fecha);
                    if (!isNaN(fechaObj.getTime())) {
                        fechaFormato = fechaObj.toLocaleDateString('es-ES');
                        horaFormato = fechaObj.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                    }
                } catch (e) {
                    console.log('Error al parsear fecha');
                }

                const asientos = Array.isArray(reserva.asientos) ? reserva.asientos.join(', ') : reserva.numero_asiento || 'N/A';

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-800">${reserva.user?.name || 'N/A'}</p>
                                <p class="text-sm text-gray-600">${reserva.user?.email || 'N/A'}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">${reserva.funcion?.pelicula?.titulo || 'N/A'}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p>${fechaFormato}</p>
                                <p class="text-sm text-gray-600">${horaFormato}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">${reserva.funcion?.sala?.nombre || 'N/A'}</td>
                        <td class="px-6 py-4">${asientos}</td>
                        <td class="px-6 py-4">
                            <span class="estado-badge estado-${reserva.estado}">
                                ${reserva.estado.charAt(0).toUpperCase() + reserva.estado.slice(1)}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 flex-wrap">
                                ${reserva.estado === 'pendiente' ? `
                                    <button onclick="cambiarEstado(${reserva.id}, 'confirmada')" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs transition">
                                        ✓ Aprobar
                                    </button>
                                    <button onclick="cambiarEstado(${reserva.id}, 'rechazada')" class="bg-orange-600 hover:bg-orange-700 text-white px-2 py-1 rounded text-xs transition">
                                        ✕ Rechazar
                                    </button>
                                ` : (reserva.estado === 'confirmada' ? `
                                    <button onclick="cambiarEstado(${reserva.id}, 'rechazada')" class="bg-orange-600 hover:bg-orange-700 text-white px-2 py-1 rounded text-xs transition">
                                        ✕ Rechazar
                                    </button>
                                ` : '')}
                                <button onclick="eliminarReserva(${reserva.id})" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs transition">
                                    🗑 Eliminar
                                </button>
                                <button onclick="verDetalles(${reserva.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs transition">
                                    👁 Ver
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function filtrarReservas() {
            const estado = document.getElementById('filtroEstado').value.toLowerCase();
            const cliente = document.getElementById('buscarCliente').value.toLowerCase();

            reservasFiltradas = reservasOriginales.filter(reserva => {
                const estadoMatch = !estado || reserva.estado.toLowerCase() === estado;
                const clienteMatch = !cliente || 
                    (reserva.user?.name?.toLowerCase().includes(cliente) || 
                     reserva.user?.email?.toLowerCase().includes(cliente));
                return estadoMatch && clienteMatch;
            });

            mostrarReservas(reservasFiltradas);
        }

        function limpiarFiltros() {
            document.getElementById('filtroEstado').value = '';
            document.getElementById('buscarCliente').value = '';
            mostrarReservas(reservasOriginales);
        }

        async function cambiarEstado(reservaId, nuevoEstado) {
            if (!confirm(`¿Cambiar estado a ${nuevoEstado}?`)) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}/admin/reservas/${reservaId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ estado: nuevoEstado })
                });

                if (!response.ok) {
                    throw new Error('Error al actualizar reserva');
                }

                alert('Estado actualizado correctamente');
                cargarReservas();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al actualizar la reserva');
            }
        }

        async function eliminarReserva(reservaId) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}/admin/reservas/${reservaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al eliminar reserva');
                }

                alert('Reserva eliminada correctamente');
                cargarReservas();
            } catch (error) {
                console.error('Error:', error);
                alert('Error al eliminar la reserva');
            }
        }

        function verDetalles(reservaId) {
            const reserva = reservasOriginales.find(r => r.id === reservaId);
            if (!reserva) return;

            // Parsear fecha
            let fechaFormato = 'N/A';
            let horaFormato = 'N/A';
            try {
                const fechaObj = new Date(reserva.funcion?.fecha);
                if (!isNaN(fechaObj.getTime())) {
                    fechaFormato = fechaObj.toLocaleDateString('es-ES');
                    horaFormato = fechaObj.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                }
            } catch (e) {
                console.log('Error al parsear fecha');
            }

            const asientos = Array.isArray(reserva.asientos) ? reserva.asientos.join(', ') : reserva.numero_asiento || 'N/A';

            const detalles = document.getElementById('detallesContenido');
            detalles.innerHTML = `
                <div>
                    <label class="text-gray-600 text-sm">Cliente</label>
                    <p class="font-semibold text-gray-800">${reserva.user?.name || 'N/A'}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Email</label>
                    <p class="font-semibold text-gray-800">${reserva.user?.email || 'N/A'}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Película</label>
                    <p class="font-semibold text-gray-800">${reserva.funcion?.pelicula?.titulo || 'N/A'}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Sala</label>
                    <p class="font-semibold text-gray-800">${reserva.funcion?.sala?.nombre || 'N/A'}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Fecha y Hora</label>
                    <p class="font-semibold text-gray-800">${fechaFormato} a las ${horaFormato}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Asientos</label>
                    <p class="font-semibold text-gray-800">${asientos}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Precio</label>
                    <p class="font-semibold text-gray-800">S/ ${parseFloat(reserva.precio || 0).toFixed(2)}</p>
                </div>
                <div>
                    <label class="text-gray-600 text-sm">Estado</label>
                    <span class="estado-badge estado-${reserva.estado}">
                        ${reserva.estado.charAt(0).toUpperCase() + reserva.estado.slice(1)}
                    </span>
                </div>
                ${reserva.comentarios ? `
                    <div>
                        <label class="text-gray-600 text-sm">Comentarios</label>
                        <p class="text-gray-800">${reserva.comentarios}</p>
                    </div>
                ` : ''}
            `;

            document.getElementById('modalDetalles').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modalDetalles').classList.add('hidden');
        }

        function handleLogout() {
            localStorage.clear();
            window.location.href = '/';
        }
    </script>
</body>
</html>
