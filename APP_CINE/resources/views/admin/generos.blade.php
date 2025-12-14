<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Géneros - CINE Admin</title>
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
                <h1 class="text-xl font-bold">CINE Admin - Gestión de Géneros</h1>
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
            <h2 class="text-3xl font-bold text-gray-800">Géneros</h2>
            <button onclick="abrirFormularioCrear()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                + Nuevo Género
            </button>
        </div>

        <!-- Buscador -->
        <div class="mb-6">
            <input 
                type="text" 
                id="buscador"
                placeholder="Buscar géneros..."
                onkeyup="filtrarGeneros()"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            >
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader"></div>
            <p class="text-gray-600 mt-4">Cargando géneros...</p>
        </div>

        <!-- Tabla de géneros -->
        <div id="contenidoGeneros" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="generosGrid">
                <!-- Se llena con JavaScript -->
            </div>
        </div>

        <!-- Sin resultados -->
        <div id="noResultados" class="hidden text-center py-12">
            <p class="text-gray-500 text-lg">No se encontraron géneros</p>
        </div>
    </div>

    <!-- Modal Crear/Editar -->
    <div id="modalFormulario" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="bg-gray-100 border-b px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitulo" class="text-xl font-bold text-gray-800">Nuevo Género</h3>
                <button onclick="cerrarFormulario()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form id="formularioGenero" onsubmit="guardarGenero(event)" class="p-6 space-y-4">
                <input type="hidden" id="generoId">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Nombre del Género *</label>
                    <input 
                        type="text" 
                        id="nombre"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="Ej: Acción"
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
        let generosOriginales = [];

        document.addEventListener('DOMContentLoaded', function() {
            token = localStorage.getItem('auth_token') || 
                   localStorage.getItem('access_token') ||
                   sessionStorage.getItem('auth_token') ||
                   sessionStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            cargarGeneros();
        });

        async function cargarGeneros() {
            try {
                const response = await fetch(`${API_URL}/generos`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar géneros');
                
                const data = await response.json();
                generosOriginales = data.data || data;
                mostrarGeneros(generosOriginales);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loader').innerHTML = '<p class="text-red-500">Error al cargar los géneros</p>';
            }
        }

        function mostrarGeneros(generos) {
            const grid = document.getElementById('generosGrid');
            grid.innerHTML = '';
            
            if (generos.length === 0) {
                document.getElementById('noResultados').classList.remove('hidden');
                document.getElementById('contenidoGeneros').classList.add('hidden');
                return;
            }

            document.getElementById('noResultados').classList.add('hidden');
            document.getElementById('loader').classList.add('hidden');
            document.getElementById('contenidoGeneros').classList.remove('hidden');

            generos.forEach(genero => {
                grid.innerHTML += `
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-600">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">${genero.nombre}</h3>
                        <div class="flex gap-3">
                            <button onclick="editarGenero(${genero.id})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                Editar
                            </button>
                            <button onclick="eliminarGenero(${genero.id})" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                                Eliminar
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        function filtrarGeneros() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const filtrados = generosOriginales.filter(g => 
                g.nombre.toLowerCase().includes(busqueda)
            );
            mostrarGeneros(filtrados);
        }

        function abrirFormularioCrear() {
            document.getElementById('generoId').value = '';
            document.getElementById('modalTitulo').textContent = 'Nuevo Género';
            document.getElementById('formularioGenero').reset();
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        function cerrarFormulario() {
            document.getElementById('modalFormulario').classList.add('hidden');
        }

        async function editarGenero(id) {
            const genero = generosOriginales.find(g => g.id === id);
            if (!genero) return;

            document.getElementById('generoId').value = id;
            document.getElementById('nombre').value = genero.nombre;
            document.getElementById('modalTitulo').textContent = 'Editar Género';
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        async function guardarGenero(e) {
            e.preventDefault();

            const id = document.getElementById('generoId').value;
            const nombre = document.getElementById('nombre').value;

            try {
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${API_URL}/generos/${id}` : `${API_URL}/generos`;

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre })
                });

                if (!response.ok) throw new Error('Error al guardar');

                cerrarFormulario();
                cargarGeneros();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function eliminarGenero(id) {
            if (!confirm('¿Deseas eliminar este género?')) return;

            try {
                const response = await fetch(`${API_URL}/generos/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al eliminar');
                cargarGeneros();
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
