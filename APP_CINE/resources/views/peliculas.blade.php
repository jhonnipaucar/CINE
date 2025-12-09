<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas - CINE App</title>
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

        .tarjeta-pelicula {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tarjeta-pelicula:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .imagen-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-2xl hover:opacity-80 transition">🎬</a>
                <h1 class="text-xl font-bold">CINE App - Películas</h1>
            </div>
            <div class="flex items-center gap-4">
                <span id="userName" class="text-sm">Usuario</span>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition text-sm">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Encabezado -->
        <div class="mb-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-2">Catálogo de Películas</h2>
            <p class="text-gray-600">Explora nuestro catálogo de películas disponibles</p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Buscador -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Buscar Película</label>
                    <input 
                        type="text" 
                        id="buscador"
                        placeholder="Buscar por título..."
                        onkeyup="filtrarPeliculas()"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                </div>

                <!-- Filtro por género -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Filtrar por Género</label>
                    <select 
                        id="filtroGenero"
                        onchange="filtrarPeliculas()"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                    >
                        <option value="">Todos los géneros</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Mensaje de error -->
        <div id="errorMessage" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded fade-in">
            <p id="errorText"></p>
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader mx-auto"></div>
            <p class="text-gray-600 mt-4">Cargando películas...</p>
        </div>

        <!-- Grid de películas -->
        <div id="gridPeliculas" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 fade-in">
            <!-- Se llena con JavaScript -->
        </div>

        <!-- Mensaje si no hay resultados -->
        <div id="noResultados" class="hidden text-center py-12">
            <p class="text-gray-500 text-lg">No se encontraron películas</p>
        </div>
    </div>

    <!-- Modal Detalle Película -->
    <div id="modalDetalle" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto fade-in">
            <div class="sticky top-0 bg-gray-100 border-b px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitulo" class="text-xl font-bold text-gray-800">Detalles de Película</h3>
                <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Imagen -->
                    <div>
                        <div id="imagenDetalle" class="w-full h-64 bg-gray-200 rounded-lg overflow-hidden">
                            <div class="imagen-placeholder w-full h-full">🎬</div>
                        </div>
                    </div>

                    <!-- Información -->
                    <div>
                        <h2 id="detalleTitulo" class="text-2xl font-bold text-gray-800 mb-4"></h2>
                        
                        <!-- Calificación TMDb -->
                        <div id="calificacionTMDb" class="hidden mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <label class="block text-gray-700 font-semibold mb-2">Calificación IMDb/TMDb</label>
                            <div class="flex items-center gap-2">
                                <span id="detalleCalificacion" class="text-2xl font-bold text-yellow-500">⭐</span>
                                <span id="detalleVotos" class="text-gray-600"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Género</label>
                            <p id="detalleGenero" class="text-gray-600"></p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Duración</label>
                            <p id="detalleDuracion" class="text-gray-600"></p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2">Sinopsis</label>
                            <p id="detalleSinopsis" class="text-gray-600 text-sm leading-relaxed"></p>
                        </div>

                        <button 
                            onclick="reservarPelicula()"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition"
                        >
                            🎟️ Reservar Entrada
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';
        let peliculasOriginal = [];
        let generosDisponibles = [];
        let peliculaSeleccionada = null;

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
                document.getElementById('userName').textContent = user.name;
            } catch (error) {
                window.location.href = '{{ route('login') }}';
            }
        }

        // Cargar películas
        async function cargarPeliculas() {
            try {
                const response = await fetch(`${API_URL}/peliculas`);
                const data = await response.json();

                if (response.ok) {
                    peliculasOriginal = data.data || data;
                    
                    // Extraer géneros únicos
                    const generos = new Set();
                    peliculasOriginal.forEach(p => {
                        if (p.generos && p.generos.length > 0) {
                            p.generos.forEach(g => generos.add(JSON.stringify(g)));
                        }
                    });
                    
                    generosDisponibles = Array.from(generos).map(g => JSON.parse(g));
                    
                    // Llenar select de géneros
                    const selectGenero = document.getElementById('filtroGenero');
                    selectGenero.innerHTML = '<option value="">Todos los géneros</option>';
                    generosDisponibles.forEach(gen => {
                        const option = document.createElement('option');
                        option.value = gen.id;
                        option.textContent = gen.nombre;
                        selectGenero.appendChild(option);
                    });

                    mostrarPeliculas(peliculasOriginal);
                } else {
                    mostrarError('Error al cargar películas');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarError('Error de conexión');
            }
        }

        // Mostrar películas en grid
        function mostrarPeliculas(peliculas) {
            const grid = document.getElementById('gridPeliculas');
            const noResultados = document.getElementById('noResultados');
            const loader = document.getElementById('loader');

            if (peliculas.length === 0) {
                grid.innerHTML = '';
                noResultados.classList.remove('hidden');
                grid.classList.add('hidden');
            } else {
                grid.innerHTML = peliculas.map(pelicula => `
                    <div class="tarjeta-pelicula bg-white rounded-lg shadow-md overflow-hidden" onclick="verDetalle(${pelicula.id})">
                        <div class="w-full h-48 bg-gray-200 overflow-hidden imagen-placeholder relative">
                            ${pelicula.url_imagen ? `<img src="${pelicula.url_imagen}" alt="${pelicula.titulo}" class="w-full h-full object-cover">` : '🎬'}
                            ${pelicula.calificacion_tmdb ? `<div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-lg text-sm font-bold">⭐ ${pelicula.calificacion_tmdb}</div>` : ''}
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">${pelicula.titulo}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">${pelicula.sinopsis}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-500">${pelicula.duracion} min</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ${pelicula.generos && pelicula.generos.length > 0 ? pelicula.generos[0].nombre : 'Sin género'}
                                </span>
                            </div>
                            ${pelicula.votos_tmdb ? `<p class="text-xs text-gray-500 mb-3">${pelicula.votos_tmdb.toLocaleString()} votos</p>` : ''}
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                `).join('');
                noResultados.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            loader.classList.add('hidden');
        }

        // Filtrar películas
        function filtrarPeliculas() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const generoId = document.getElementById('filtroGenero').value;

            const filtradas = peliculasOriginal.filter(p => {
                const coincideBusqueda = p.titulo.toLowerCase().includes(busqueda);
                const coincideGenero = !generoId || (p.generos && p.generos.some(g => g.id == generoId));
                return coincideBusqueda && coincideGenero;
            });

            mostrarPeliculas(filtradas);
        }

        // Ver detalle de película
        function verDetalle(id) {
            const pelicula = peliculasOriginal.find(p => p.id === id);
            if (!pelicula) return;

            peliculaSeleccionada = pelicula;

            document.getElementById('modalTitulo').textContent = pelicula.titulo;
            document.getElementById('detalleTitulo').textContent = pelicula.titulo;
            document.getElementById('detalleDuracion').textContent = `${pelicula.duracion} minutos`;
            document.getElementById('detalleSinopsis').textContent = pelicula.sinopsis || pelicula.descripcion || 'Sin sinopsis disponible';
            document.getElementById('detalleGenero').textContent = pelicula.generos && pelicula.generos.length > 0 
                ? pelicula.generos.map(g => g.nombre).join(', ')
                : 'Sin género asignado';

            // Mostrar calificación TMDb si disponible
            const calificacionDiv = document.getElementById('calificacionTMDb');
            if (pelicula.calificacion_tmdb) {
                document.getElementById('detalleCalificacion').textContent = `⭐ ${pelicula.calificacion_tmdb}`;
                document.getElementById('detalleVotos').textContent = `${pelicula.votos_tmdb ? pelicula.votos_tmdb.toLocaleString() + ' votos' : 'Calificación de TMDb'}`;
                calificacionDiv.classList.remove('hidden');
            } else {
                calificacionDiv.classList.add('hidden');
            }

            if (pelicula.url_imagen) {
                document.getElementById('imagenDetalle').innerHTML = `<img src="${pelicula.url_imagen}" alt="${pelicula.titulo}" class="w-full h-full object-cover">`;
            } else {
                document.getElementById('imagenDetalle').innerHTML = '<div class="imagen-placeholder w-full h-full">🎬</div>';
            }

            document.getElementById('modalDetalle').classList.remove('hidden');
        }

        // Cerrar modal
        function cerrarModal() {
            document.getElementById('modalDetalle').classList.add('hidden');
            peliculaSeleccionada = null;
        }

        // Reservar película
        function reservarPelicula() {
            if (!peliculaSeleccionada) return;
            alert(`Funcionalidad de reserva próximamente disponible para: ${peliculaSeleccionada.titulo}`);
            // Aquí se podría redirigir a una página de reserva
        }

        // Mostrar error
        function mostrarError(mensaje) {
            const errorDiv = document.getElementById('errorMessage');
            document.getElementById('errorText').textContent = mensaje;
            errorDiv.classList.remove('hidden');
            document.getElementById('loader').classList.add('hidden');
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 5000);
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
            cargarPeliculas();
        });

        // Cerrar modal al hacer click fuera
        document.getElementById('modalDetalle').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });
    </script>
</body>
</html>
