<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Salas - CINE Admin</title>
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎬</span>
                <h1 class="text-xl font-bold">CINE Admin - Gestión de Salas</h1>
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
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Salas de Cine</h2>
            <button onclick="abrirFormularioCrear()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                + Nueva Sala
            </button>
        </div>

        <!-- Buscador -->
        <div class="mb-6">
            <input 
                type="text" 
                id="buscador"
                placeholder="Buscar salas por nombre..."
                onkeyup="filtrarSalas()"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            >
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader"></div>
            <p class="text-gray-600 mt-4">Cargando salas...</p>
        </div>

        <!-- Tabla de salas -->
        <div id="contenidoSalas" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="salasGrid">
                <!-- Se llena con JavaScript -->
            </div>
        </div>

        <!-- Sin resultados -->
        <div id="noResultados" class="hidden text-center py-12">
            <p class="text-gray-500 text-lg">No se encontraron salas</p>
        </div>
    </div>

    <!-- Modal Crear/Editar -->
    <div id="modalFormulario" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="bg-gray-100 border-b px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitulo" class="text-xl font-bold text-gray-800">Nueva Sala</h3>
                <button onclick="cerrarFormulario()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form id="formularioSala" onsubmit="guardarSala(event)" class="p-6 space-y-4">
                <input type="hidden" id="salaId">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nombre de la Sala *</label>
                    <input 
                        type="text" 
                        id="nombre"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="Ej: Sala 1"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Capacidad (asientos) *</label>
                    <input 
                        type="number" 
                        id="capacidad"
                        required
                        min="1"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="Ej: 50"
                    >
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="cerrarFormulario()" class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_URL = 'http://127.0.0.1:8000/api';
        let token = '';
        let salasOriginales = [];

        document.addEventListener('DOMContentLoaded', function() {
            token = localStorage.getItem('auth_token') || 
                   localStorage.getItem('access_token') ||
                   sessionStorage.getItem('auth_token') ||
                   sessionStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            cargarSalas();
        });

        async function cargarSalas() {
            try {
                const response = await fetch(`${API_URL}/salas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar salas');
                
                const data = await response.json();
                salasOriginales = data.data || data;
                mostrarSalas(salasOriginales);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loader').innerHTML = '<p class="text-red-500">Error al cargar las salas</p>';
            }
        }

        function mostrarSalas(salas) {
            const grid = document.getElementById('salasGrid');
            grid.innerHTML = '';
            
            if (salas.length === 0) {
                document.getElementById('noResultados').classList.remove('hidden');
                document.getElementById('contenidoSalas').classList.add('hidden');
                return;
            }

            document.getElementById('noResultados').classList.add('hidden');
            document.getElementById('loader').classList.add('hidden');
            document.getElementById('contenidoSalas').classList.remove('hidden');

            salas.forEach(sala => {
                grid.innerHTML += `
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-600">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">${sala.nombre}</h3>
                        <p class="text-gray-600 mb-4">
                            <span class="inline-block bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                ${sala.capacidad} asientos
                            </span>
                        </p>
                        <div class="flex gap-3">
                            <button onclick="editarSala(${sala.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                Editar
                            </button>
                            <button onclick="eliminarSala(${sala.id})" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                Eliminar
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        function filtrarSalas() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const filtrados = salasOriginales.filter(s => 
                s.nombre.toLowerCase().includes(busqueda)
            );
            mostrarSalas(filtrados);
        }

        function abrirFormularioCrear() {
            document.getElementById('salaId').value = '';
            document.getElementById('modalTitulo').textContent = 'Nueva Sala';
            document.getElementById('formularioSala').reset();
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        function cerrarFormulario() {
            document.getElementById('modalFormulario').classList.add('hidden');
        }

        async function editarSala(id) {
            const sala = salasOriginales.find(s => s.id === id);
            if (!sala) return;

            document.getElementById('salaId').value = id;
            document.getElementById('nombre').value = sala.nombre;
            document.getElementById('capacidad').value = sala.capacidad;
            document.getElementById('modalTitulo').textContent = 'Editar Sala';
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        async function guardarSala(e) {
            e.preventDefault();

            const id = document.getElementById('salaId').value;
            const nombre = document.getElementById('nombre').value;
            const capacidad = document.getElementById('capacidad').value;

            try {
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${API_URL}/salas/${id}` : `${API_URL}/salas`;

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre, capacidad: parseInt(capacidad) })
                });

                if (!response.ok) throw new Error('Error al guardar');

                cerrarFormulario();
                cargarSalas();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function eliminarSala(id) {
            if (!confirm('¿Deseas eliminar esta sala?')) return;

            try {
                const response = await fetch(`${API_URL}/salas/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al eliminar');
                cargarSalas();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function handleLogout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('access_token');
            localStorage.removeItem('user');
            localStorage.removeItem('user_data');
            window.location.href = '/login';
        }
    </script>
</body>
</html>
