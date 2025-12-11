<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - CINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
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

        .asiento {
            width: 40px;
            height: 40px;
            margin: 4px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.2s;
        }

        .asiento.disponible {
            background-color: #4ade80;
            border-color: #22c55e;
            color: white;
        }

        .asiento.disponible:hover {
            background-color: #22c55e;
            transform: scale(1.1);
        }

        .asiento.reservado {
            background-color: #ef4444;
            border-color: #dc2626;
            color: white;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .asiento.seleccionado {
            background-color: #3b82f6;
            border-color: #1d4ed8;
            color: white;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .asiento.pasillo {
            margin: 4px 12px;
            border: none;
            background: none;
            cursor: default;
        }

        .pantalla {
            text-align: center;
            font-weight: bold;
            color: #666;
            margin-bottom: 20px;
            padding: 10px;
            border: 2px solid #666;
            border-radius: 4px;
        }

        .tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e5e7eb;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            color: #666;
            font-weight: 500;
        }

        .tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .funcion-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .funcion-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: #2563eb;
        }

        .funcion-card.selected {
            border: 2px solid #2563eb;
            background-color: #eff6ff;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎬</span>
                <h1 class="text-xl font-bold">CINE App</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="/peliculas" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Películas</a>
                <a href="/generos" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Géneros</a>
                <a href="/funciones" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Funciones</a>
                <a href="/salas" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Salas</a>
                <a href="/reservas" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Mis Reservas</a>
                <a href="/perfil" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition">Mi Perfil</a>
                <a href="/admin/peliculas" id="adminLink" class="hover:bg-white hover:bg-opacity-20 px-4 py-2 rounded-lg transition" style="display:none;">Gestión Admin</a>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">Salir</button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Tabs -->
        <div class="mb-6">
            <div class="tabs">
                <div class="tab active" onclick="cambiarTab('nuevaReserva')">
                    🎟️ Nueva Reserva
                </div>
                <div class="tab" onclick="cambiarTab('misReservas')">
                    📋 Mis Reservas
                </div>
            </div>
        </div>

        <!-- Tab: Nueva Reserva -->
        <div id="nuevaReserva" class="tab-content active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Listado de Funciones -->
                <div class="lg:col-span-1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Funciones Disponibles</h2>

                    <div id="loaderFunciones" class="text-center py-12">
                        <div class="loader mx-auto"></div>
                        <p class="text-gray-600 mt-4">Cargando funciones...</p>
                    </div>

                    <div id="listadoFunciones" class="hidden"></div>

                    <div id="errorFunciones" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <p id="errorTextFunciones"></p>
                    </div>
                </div>

                <!-- Selección de Asientos -->
                <div class="lg:col-span-2">
                    <div id="seleccionAsientos" class="hidden">
                        <!-- Info de Función Seleccionada -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-6 border border-blue-200">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Película</p>
                                    <p id="seleccionPelicula" class="font-bold text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Fecha</p>
                                    <p id="seleccionFecha" class="font-bold text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Hora</p>
                                    <p id="seleccionHora" class="font-bold text-gray-800"></p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Sala</p>
                                    <p id="seleccionSala" class="font-bold text-gray-800"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="flex gap-6 mb-6 flex-wrap justify-center">
                            <div class="flex items-center gap-2">
                                <div class="asiento disponible">A</div>
                                <span class="text-sm text-gray-700">Disponible</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="asiento seleccionado">B</div>
                                <span class="text-sm text-gray-700">Seleccionado</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="asiento reservado">X</div>
                                <span class="text-sm text-gray-700">Reservado</span>
                            </div>
                        </div>

                        <!-- Mapa de Asientos -->
                        <div class="bg-white rounded-lg p-8 border-2 border-gray-200">
                            <div class="pantalla">
                                🎥 PANTALLA 🎥
                            </div>
                            <div id="mapaAsientos" class="flex flex-col gap-2 justify-center"></div>
                        </div>

                        <!-- Resumen de Selección -->
                        <div id="resumenSeleccion" class="mt-6 bg-blue-50 rounded-lg p-6 border border-blue-200 hidden">
                            <h3 class="font-bold text-gray-800 mb-4">Resumen de Reserva</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Asientos</p>
                                    <p id="resumenAsientos" class="font-bold text-lg text-blue-600">-</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Cantidad</p>
                                    <p id="resumenCantidad" class="font-bold text-lg text-blue-600">0</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Precio Unitario</p>
                                    <p id="resumenPrecioUnitario" class="font-bold text-lg text-blue-600">$0</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Total</p>
                                    <p id="resumenTotal" class="font-bold text-lg text-blue-600">$0</p>
                                </div>
                            </div>
                            <button 
                                onclick="procesarReserva()"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition"
                            >
                                ✓ Confirmar Reserva
                            </button>
                            <button 
                                onclick="limpiarSeleccion()"
                                class="w-full mt-2 bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold transition"
                            >
                                ✕ Cancelar
                            </button>
                        </div>
                    </div>

                    <div id="noFuncionSeleccionada" class="text-center py-12 text-gray-600">
                        <p class="text-lg">Selecciona una función para reservar asientos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Mis Reservas -->
        <div id="misReservas" class="tab-content">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Mis Reservas</h2>

            <div id="loaderReservas" class="text-center py-12">
                <div class="loader mx-auto"></div>
                <p class="text-gray-600 mt-4">Cargando mis reservas...</p>
            </div>

            <div id="listadoReservas" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            <div id="noReservas" class="hidden text-center py-12 bg-gray-100 rounded-lg">
                <p class="text-gray-600 text-lg mb-4">No tienes reservas</p>
                <button 
                    onclick="cambiarTab('nuevaReserva')"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold"
                >
                    🎟️ Hacer una Reserva
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Éxito -->
    <div id="modalExito" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-sm mx-4 fade-in text-center p-8">
            <div class="text-5xl mb-4">✅</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">¡Reserva Confirmada!</h3>
            <p id="textoExito" class="text-gray-600 mb-6"></p>
            <p class="text-sm text-gray-500 mb-6">Se ha enviado una confirmación a tu email.</p>
            <button 
                onclick="cerrarModalExito()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition"
            >
                Continuar
            </button>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';
        let funciones = [];
        let funcionSeleccionada = null;
        let asientosSeleccionados = [];
        let precioEntrada = 0;

        // Verificar autenticación
        function verificarAutenticacion() {
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user_data');

            if (!token || !userData) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            try {
                const user = JSON.parse(userData);
                if (user.role === 'admin') {
                    document.getElementById('adminLink').style.display = 'inline-block';
                }
            } catch (error) {
                window.location.href = '{{ route('login') }}';
            }
        }

        // Cambiar tab
        function cambiarTab(tabName) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('active');
            });

            // Desactivar todos los botones de tab
            document.querySelectorAll('.tab').forEach(el => {
                el.classList.remove('active');
            });

            // Mostrar tab seleccionado
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');

            // Cargar datos si es necesario
            if (tabName === 'misReservas') {
                cargarMisReservas();
            }
        }

        // Cargar funciones disponibles
        async function cargarFunciones() {
            document.getElementById('loaderFunciones').classList.remove('hidden');
            document.getElementById('listadoFunciones').classList.add('hidden');
            
            try {
                console.log('Cargando funciones desde:', API_URL + '/funciones');
                const response = await fetch(`${API_URL}/funciones`);
                
                console.log('Respuesta status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                console.log('Funciones cargadas:', data);

                funciones = data.data || data;
                mostrarFunciones();
            } catch (error) {
                console.error('Error al cargar funciones:', error);
                mostrarErrorFunciones('Error: ' + error.message);
            }
        }

        // Mostrar funciones en listado
        function mostrarFunciones() {
            const contenedor = document.getElementById('listadoFunciones');
            
            if (!funciones || funciones.length === 0) {
                contenedor.innerHTML = '<p class="text-gray-600">No hay funciones disponibles</p>';
                contenedor.classList.remove('hidden');
                document.getElementById('loaderFunciones').classList.add('hidden');
                return;
            }

            contenedor.innerHTML = funciones.map((funcion, index) => {
                return `
                    <div class="funcion-card" onclick="seleccionarFuncion(${index})">
                        <h3 class="font-bold text-gray-800 mb-2">${funcion.pelicula.titulo}</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                            <div>📅 ${new Date(funcion.fecha).toLocaleDateString('es-ES')}</div>
                            <div>🕐 ${new Date(funcion.fecha).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</div>
                            <div>🪑 ${funcion.sala.nombre}</div>
                            <div>💵 $${parseFloat(funcion.precio).toFixed(2)}</div>
                        </div>
                    </div>
                `;
            }).join('');

            contenedor.classList.remove('hidden');
            document.getElementById('loaderFunciones').classList.add('hidden');
        }

        // Seleccionar función
        function seleccionarFuncion(index) {
            funcionSeleccionada = funciones[index];
            precioEntrada = parseFloat(funcionSeleccionada.precio);
            asientosSeleccionados = [];

            // Actualizar UI
            document.querySelectorAll('.funcion-card').forEach((el, i) => {
                el.classList.toggle('selected', i === index);
            });

            // Mostrar selección de asientos
            document.getElementById('seleccionAsientos').classList.remove('hidden');
            document.getElementById('noFuncionSeleccionada').classList.add('hidden');
            document.getElementById('resumenSeleccion').classList.add('hidden');

            // Actualizar info de función
            document.getElementById('seleccionPelicula').textContent = funcionSeleccionada.pelicula.titulo;
            document.getElementById('seleccionFecha').textContent = new Date(funcionSeleccionada.fecha).toLocaleDateString('es-ES');
            document.getElementById('seleccionHora').textContent = funcionSeleccionada.hora;
            document.getElementById('seleccionSala').textContent = `Sala ${funcionSeleccionada.sala.numero}`;

            // Generar mapa de asientos
            generarMapaAsientos();

            // Scroll a asientos
            document.getElementById('mapaAsientos').scrollIntoView({ behavior: 'smooth' });
        }

        // Generar mapa de asientos
        function generarMapaAsientos() {
            const contenedor = document.getElementById('mapaAsientos');
            contenedor.innerHTML = '';

            const filas = 8;
            const columnasXFila = 12;
            const asientosReservados = funcionSeleccionada.reservas.map(r => r.numero_asiento);

            for (let fila = 1; fila <= filas; fila++) {
                const filaDiv = document.createElement('div');
                filaDiv.className = 'flex justify-center gap-1';

                for (let col = 1; col <= columnasXFila; col++) {
                    const numeroAsiento = String.fromCharCode(64 + fila) + col;

                    if (col === 7) {
                        const pasillo = document.createElement('div');
                        pasillo.className = 'asiento pasillo';
                        filaDiv.appendChild(pasillo);
                    }

                    const asiento = document.createElement('div');
                    asiento.className = 'asiento';
                    asiento.textContent = col;

                    if (asientosReservados.includes(numeroAsiento)) {
                        asiento.classList.add('reservado');
                    } else {
                        asiento.classList.add('disponible');
                        asiento.onclick = () => toggleAsiento(numeroAsiento, asiento);
                    }

                    filaDiv.appendChild(asiento);
                }

                contenedor.appendChild(filaDiv);
            }
        }

        // Toggle asiento seleccionado
        function toggleAsiento(numeroAsiento, elemento) {
            const index = asientosSeleccionados.indexOf(numeroAsiento);

            if (index > -1) {
                asientosSeleccionados.splice(index, 1);
                elemento.classList.remove('seleccionado');
            } else {
                asientosSeleccionados.push(numeroAsiento);
                elemento.classList.add('seleccionado');
            }

            actualizarResumen();
        }

        // Actualizar resumen
        function actualizarResumen() {
            if (asientosSeleccionados.length === 0) {
                document.getElementById('resumenSeleccion').classList.add('hidden');
                return;
            }

            const cantidad = asientosSeleccionados.length;
            const total = cantidad * precioEntrada;

            document.getElementById('resumenAsientos').textContent = asientosSeleccionados.sort().join(', ');
            document.getElementById('resumenCantidad').textContent = cantidad;
            document.getElementById('resumenPrecioUnitario').textContent = `$${precioEntrada.toFixed(2)}`;
            document.getElementById('resumenTotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('resumenSeleccion').classList.remove('hidden');
        }

        // Procesar reserva
        async function procesarReserva() {
            if (asientosSeleccionados.length === 0) {
                alert('Selecciona al menos un asiento');
                return;
            }

            const token = localStorage.getItem('auth_token');
            const datos = {
                funcion_id: funcionSeleccionada.id,
                asientos: asientosSeleccionados,
                estado: 'confirmada'
            };

            const token = localStorage.getItem('auth_token');

            if (!token) {
                alert('Debes estar logueado para hacer una reserva');
                window.location.href = '{{ route('login') }}';
                return;
            }

            try {
                console.log('Enviando reserva a:', `${API_URL}/reservas`);
                console.log('Datos:', datos);

                const response = await fetch(`${API_URL}/reservas`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(datos)
                });

                console.log('Respuesta status:', response.status);

                const data = await response.json();

                console.log('Datos de respuesta:', data);

                if (response.ok) {
                    mostrarExito(`Asientos: ${asientosSeleccionados.join(', ')}`);
                    limpiarSeleccion();
                    setTimeout(() => {
                        cargarFunciones();
                    }, 2000);
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error completo:', error);
                alert('Error de conexión: ' + error.message);
            }
        }

        // Limpiar selección
        function limpiarSeleccion() {
            asientosSeleccionados = [];
            document.getElementById('resumenSeleccion').classList.add('hidden');
            document.querySelectorAll('.asiento.seleccionado').forEach(el => {
                el.classList.remove('seleccionado');
            });
        }

        // Cargar mis reservas
        async function cargarMisReservas() {
            const token = localStorage.getItem('auth_token');

            try {
                const response = await fetch(`${API_URL}/reservas`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    const reservas = data.data || data;
                    mostrarMisReservas(reservas);
                } else {
                    alert('Error al cargar reservas');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        }

        // Mostrar mis reservas
        function mostrarMisReservas(reservas) {
            const contenedor = document.getElementById('listadoReservas');

            document.getElementById('loaderReservas').classList.add('hidden');

            if (reservas.length === 0) {
                document.getElementById('noReservas').classList.remove('hidden');
                return;
            }

            contenedor.innerHTML = reservas.map(reserva => {
                const fecha = new Date(reserva.funcion.fecha);
                const estado = {
                    'pendiente': { bg: 'bg-yellow-100', text: 'text-yellow-800', label: '⏳ Pendiente' },
                    'confirmada': { bg: 'bg-green-100', text: 'text-green-800', label: '✅ Confirmada' },
                    'cancelada': { bg: 'bg-red-100', text: 'text-red-800', label: '❌ Cancelada' }
                }[reserva.estado] || { bg: 'bg-gray-100', text: 'text-gray-800', label: reserva.estado };

                return `
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-600">
                        <div class="p-6">
                            <h3 class="font-bold text-lg text-gray-800 mb-2">${reserva.funcion.pelicula.titulo}</h3>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div>📅 ${fecha.toLocaleDateString('es-ES')}</div>
                                <div>🕐 ${reserva.funcion.hora}</div>
                                <div>🪑 Sala ${reserva.funcion.sala.numero} - Asiento ${reserva.numero_asiento}</div>
                                <div>💵 $${parseFloat(reserva.precio).toFixed(2)}</div>
                            </div>
                            <div class="mb-4">
                                <span class="${estado.bg} ${estado.text} px-3 py-1 rounded-full text-xs font-semibold">
                                    ${estado.label}
                                </span>
                            </div>
                            ${reserva.estado === 'confirmada' ? `
                                <button 
                                    onclick="cancelarReserva(${reserva.id})"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition"
                                >
                                    Cancelar Reserva
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');

            contenedor.classList.remove('hidden');
        }

        // Cancelar reserva
        async function cancelarReserva(reservaId) {
            if (!confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
                return;
            }

            const token = localStorage.getItem('auth_token');

            try {
                const response = await fetch(`${API_URL}/reservas/${reservaId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    alert('Reserva cancelada');
                    cargarMisReservas();
                } else {
                    alert('Error al cancelar reserva');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        }

        // Mostrar error
        function mostrarErrorFunciones(mensaje) {
            document.getElementById('errorTextFunciones').textContent = mensaje;
            document.getElementById('errorFunciones').classList.remove('hidden');
            document.getElementById('loaderFunciones').classList.add('hidden');
        }

        // Mostrar éxito
        function mostrarExito(asientos) {
            document.getElementById('textoExito').textContent = `Has reservado los asientos: ${asientos}`;
            document.getElementById('modalExito').classList.remove('hidden');
        }

        // Cerrar modal éxito
        function cerrarModalExito() {
            document.getElementById('modalExito').classList.add('hidden');
            cambiarTab('misReservas');
        }

        // Logout
        function handleLogout() {
            const token = localStorage.getItem('auth_token');
            fetch(`${API_URL}/auth/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            }).finally(() => {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
                window.location.href = '{{ route('login') }}';
            });
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            verificarAutenticacion();
            cargarFunciones();
        });
    </script>
</body>
</html>
