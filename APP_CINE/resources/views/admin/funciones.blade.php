<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Funciones - CINE Admin</title>
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
                <h1 class="text-xl font-bold">CINE Admin - Gestión de Funciones</h1>
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
        <div id="debugInfo" style="background: #f0f0f0; padding: 10px; margin-bottom: 20px; border-radius: 5px; display: none;">
            <small id="debugText"></small>
        </div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Funciones</h2>
            <button onclick="abrirFormularioCrear()" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                + Nueva Función
            </button>
        </div>

        <!-- Buscador -->
        <div class="mb-6">
            <input 
                type="text" 
                id="buscador"
                placeholder="Buscar funciones..."
                onkeyup="filtrarFunciones()"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            >
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader"></div>
            <p class="text-gray-600 mt-4">Cargando funciones...</p>
        </div>

        <!-- Tabla de funciones -->
        <div id="contenidoFunciones" class="hidden">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Película</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Sala</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Fecha</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Precio</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody" class="divide-y divide-gray-200">
                        <!-- Se llena con JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sin resultados -->
        <div id="noResultados" class="hidden text-center py-12">
            <p class="text-gray-500 text-lg">No se encontraron funciones</p>
        </div>
    </div>

    <!-- Modal Crear/Editar -->
    <div id="modalFormulario" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gray-100 border-b px-6 py-4 flex justify-between items-center sticky top-0">
                <h3 id="modalTitulo" class="text-xl font-bold text-gray-800">Nueva Función</h3>
                <button onclick="cerrarFormulario()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form id="formularioFuncion" onsubmit="guardarFuncion(event)" class="p-6 space-y-4">
                <input type="hidden" id="funcionId">

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Película *</label>
                    <select 
                        id="pelicula_id"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                        <option value="">Seleccionar película...</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Sala *</label>
                    <select 
                        id="sala_id"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                        <option value="">Seleccionar sala...</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Fecha y Hora *</label>
                    <input 
                        type="datetime-local" 
                        id="fecha"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Precio (S/) *</label>
                    <input 
                        type="number" 
                        id="precio"
                        required
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="10.00"
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
        let funcionesOriginales = [];
        let peliculas = [];
        let salas = [];

        document.addEventListener('DOMContentLoaded', function() {
            token = localStorage.getItem('auth_token') || 
                   localStorage.getItem('access_token') ||
                   sessionStorage.getItem('auth_token') ||
                   sessionStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            
            // Establecer fecha mínima a hoy para evitar crear funciones en fechas anteriores
            const hoy = new Date();
            const año = hoy.getFullYear();
            const mes = String(hoy.getMonth() + 1).padStart(2, '0');
            const día = String(hoy.getDate()).padStart(2, '0');
            const fechaMinima = `${año}-${mes}-${día}T00:00`;
            document.getElementById('fecha').min = fechaMinima;
            
            cargarDatos();
        });

        async function cargarDatos() {
            try {
                // Cargar películas
                const resPeliculas = await fetch(`${API_URL}/peliculas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (resPeliculas.ok) {
                    const dataPeliculas = await resPeliculas.json();
                    
                    console.log('=== PELÍCULAS ===');
                    console.log('Response completo:', dataPeliculas);
                    console.log('dataPeliculas.data:', dataPeliculas.data);
                    console.log('Array.isArray(dataPeliculas.data):', Array.isArray(dataPeliculas.data));
                    
                    peliculas = dataPeliculas.data || dataPeliculas;
                    
                    console.log('Películas asignadas:', peliculas);
                    console.log('Películas length:', peliculas?.length);
                }

                // Cargar salas
                const resSalas = await fetch(`${API_URL}/salas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (resSalas.ok) {
                    const dataSalas = await resSalas.json();
                    
                    console.log('=== SALAS ===');
                    console.log('Response completo:', dataSalas);
                    console.log('dataSalas.data:', dataSalas.data);
                    console.log('Array.isArray(dataSalas.data):', Array.isArray(dataSalas.data));
                    
                    salas = dataSalas.data || dataSalas;
                    
                    console.log('Salas asignadas:', salas);
                    console.log('Salas length:', salas?.length);
                }

                // Llenar selectores DESPUÉS de tener los datos
                llenarSelectPeliculas();
                llenarSelectSalas();

                // Cargar funciones
                await cargarFunciones();
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loader').innerHTML = '<p class="text-red-500">Error al cargar los datos</p>';
            }
        }

        function llenarSelectPeliculas() {
            const select = document.getElementById('pelicula_id');
            select.innerHTML = '<option value="">Seleccionar película...</option>';
            if (!peliculas || !Array.isArray(peliculas)) {
                console.error('Películas no es un array:', peliculas);
                return;
            }
            peliculas.forEach(p => {
                const option = document.createElement('option');
                option.value = p.id;
                option.textContent = p.titulo;
                select.appendChild(option);
            });
        }

        function llenarSelectSalas() {
            const select = document.getElementById('sala_id');
            select.innerHTML = '<option value="">Seleccionar sala...</option>';
            if (!salas || !Array.isArray(salas)) {
                console.error('Salas no es un array:', salas);
                return;
            }
            salas.forEach(s => {
                const option = document.createElement('option');
                option.value = s.id;
                option.textContent = s.nombre;
                select.appendChild(option);
            });
        }

        async function cargarFunciones() {
            try {
                const response = await fetch(`${API_URL}/funciones`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar funciones');
                
                const data = await response.json();
                funcionesOriginales = data.data || data;
                console.log('Funciones cargadas:', funcionesOriginales);
                console.log('Películas en scope:', peliculas);
                console.log('Salas en scope:', salas);
                mostrarFunciones(funcionesOriginales, peliculas, salas);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loader').innerHTML = '<p class="text-red-500">Error al cargar las funciones</p>';
            }
        }

        function mostrarFunciones(funciones, peliculasParam, salasParam) {
            const tbody = document.getElementById('tablaBody');
            tbody.innerHTML = '';
            
            // Debug
            const debugDiv = document.getElementById('debugInfo');
            const debugText = document.getElementById('debugText');
            debugDiv.style.display = 'block';
            debugText.innerHTML = `Películas: ${peliculasParam?.length || 0} | Salas: ${salasParam?.length || 0} | Funciones: ${funciones?.length || 0}`;
            
            if (!funciones || funciones.length === 0) {
                document.getElementById('noResultados').classList.remove('hidden');
                document.getElementById('contenidoFunciones').classList.add('hidden');
                return;
            }

            document.getElementById('noResultados').classList.add('hidden');
            document.getElementById('loader').classList.add('hidden');
            document.getElementById('contenidoFunciones').classList.remove('hidden');

            console.log('=== mostrarFunciones ===');
            console.log('peliculasParam:', peliculasParam);
            console.log('salasParam:', salasParam);
            
            let peliculasLista = Array.isArray(peliculasParam) ? peliculasParam : [];
            let salasLista = Array.isArray(salasParam) ? salasParam : [];
            
            console.log('peliculasLista final:', peliculasLista);
            console.log('salasLista final:', salasLista);

            // DEBUG: Ver estructura de primer elemento
            if (peliculasLista.length > 0) {
                console.log('Primer elemento películas:', peliculasLista[0]);
                console.log('Tipo de peliculasLista[0].id:', typeof peliculasLista[0].id);
            }
            if (salasLista.length > 0) {
                console.log('Primer elemento salas:', salasLista[0]);
                console.log('Tipo de salasLista[0].id:', typeof salasLista[0].id);
            }

            funciones.forEach(funcion => {
                console.log('Función:', funcion.id, 'pelicula_id:', funcion.pelicula_id, 'sala_id:', funcion.sala_id);
                console.log('  Tipo pelicula_id:', typeof funcion.pelicula_id);
                console.log('  Tipo sala_id:', typeof funcion.sala_id);
                
                let pelicula = null;
                let sala = null;
                
                if (peliculasLista.length > 0) {
                    pelicula = peliculasLista.find(p => {
                        const match = p.id == funcion.pelicula_id; // Comparar con ==, no ===
                        console.log(`  Comparando p.id(${p.id} ${typeof p.id}) == funcion.pelicula_id(${funcion.pelicula_id} ${typeof funcion.pelicula_id}): ${match}`);
                        return match;
                    });
                    console.log('  Película encontrada:', pelicula?.titulo || 'NO ENCONTRADA');
                }
                
                if (salasLista.length > 0) {
                    sala = salasLista.find(s => {
                        const match = s.id == funcion.sala_id; // Comparar con ==, no ===
                        console.log(`  Comparando s.id(${s.id} ${typeof s.id}) == funcion.sala_id(${funcion.sala_id} ${typeof funcion.sala_id}): ${match}`);
                        return match;
                    });
                    console.log('  Sala encontrada:', sala?.nombre || 'NO ENCONTRADA');
                }
                
                // Parsear fecha
                let fechaFormato = funcion.fecha || 'N/A';
                if (fechaFormato !== 'N/A') {
                    try {
                        const fecha = new Date(fechaFormato);
                        const año = fecha.getFullYear();
                        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                        const día = String(fecha.getDate()).padStart(2, '0');
                        const horas = String(fecha.getHours()).padStart(2, '0');
                        const minutos = String(fecha.getMinutes()).padStart(2, '0');
                        fechaFormato = `${año}-${mes}-${día} ${horas}:${minutos}`;
                    } catch (e) {
                        fechaFormato = funcion.fecha;
                    }
                }
                
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">${pelicula ? pelicula.titulo : 'N/A'}</td>
                        <td class="px-6 py-4">${sala ? sala.nombre : 'N/A'}</td>
                        <td class="px-6 py-4">${fechaFormato}</td>
                        <td class="px-6 py-4">S/ ${parseFloat(funcion.precio).toFixed(2)}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="editarFuncion(${funcion.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                    Editar
                                </button>
                                <button onclick="eliminarFuncion(${funcion.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function filtrarFunciones() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const filtrados = funcionesOriginales.filter(f => {
                const pelicula = peliculas.find(p => p.id === f.pelicula_id);
                return pelicula && pelicula.titulo.toLowerCase().includes(busqueda);
            });
            mostrarFunciones(filtrados, peliculas, salas);
        }

        function abrirFormularioCrear() {
            document.getElementById('funcionId').value = '';
            document.getElementById('modalTitulo').textContent = 'Nueva Función';
            document.getElementById('formularioFuncion').reset();
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        function cerrarFormulario() {
            document.getElementById('modalFormulario').classList.add('hidden');
        }

        async function editarFuncion(id) {
            const funcion = funcionesOriginales.find(f => f.id === id);
            if (!funcion) return;

            document.getElementById('funcionId').value = id;
            document.getElementById('pelicula_id').value = funcion.pelicula_id;
            document.getElementById('sala_id').value = funcion.sala_id;
            document.getElementById('fecha').value = funcion.fecha ? funcion.fecha.slice(0, 16) : '';
            document.getElementById('precio').value = funcion.precio;
            document.getElementById('modalTitulo').textContent = 'Editar Función';
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        async function guardarFuncion(e) {
            e.preventDefault();

            const id = document.getElementById('funcionId').value;
            const pelicula_id = document.getElementById('pelicula_id').value;
            const sala_id = document.getElementById('sala_id').value;
            const fechaInput = document.getElementById('fecha').value;
            const precio = document.getElementById('precio').value;

            // Convertir datetime-local (2025-12-14T15:30) a formato Y-m-d H:i (2025-12-14 15:30)
            const [fecha_part, hora_part] = fechaInput.split('T');
            const fecha = `${fecha_part} ${hora_part}`;

            if (!pelicula_id) {
                alert('Selecciona una película');
                return;
            }

            if (!sala_id) {
                alert('Selecciona una sala');
                return;
            }

            try {
                const method = id ? 'PUT' : 'POST';
                const url = id ? `${API_URL}/funciones/${id}` : `${API_URL}/funciones`;

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        pelicula_id: parseInt(pelicula_id),
                        sala_id: parseInt(sala_id),
                        fecha: fecha,
                        precio: parseFloat(precio)
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al guardar');
                }

                cerrarFormulario();
                cargarFunciones();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        async function eliminarFuncion(id) {
            if (!confirm('¿Deseas eliminar esta función?')) return;

            try {
                const response = await fetch(`${API_URL}/funciones/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!response.ok) throw new Error('Error al eliminar');
                cargarFunciones();
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
