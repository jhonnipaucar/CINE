<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Películas - CINE</title>
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

        .pelicula-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .pelicula-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }

        .pelicula-poster {
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            position: relative;
            overflow: hidden;
        }

        .pelicula-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255, 193, 7, 0.9);
            color: #333;
            padding: 8px 12px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pelicula-info {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .pelicula-titulo {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .pelicula-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #666;
        }

        .pelicula-sinopsis {
            color: #666;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            flex-grow: 1;
        }

        .pelicula-generos {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .genero-tag {
            background: #e0e7ff;
            color: #4f46e5;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .btn-ver-detalles {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
            font-size: 14px;
        }

        .btn-ver-detalles:hover {
            opacity: 0.9;
        }

        .filtros-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .filtro-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filtro-input {
            flex: 1;
            min-width: 200px;
            padding: 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .filtro-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .filtro-select {
            padding: 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .filtro-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-filtro {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-filtro:hover {
            background: #5568d3;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-detalle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .modal-poster {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 96px;
            overflow: hidden;
        }

        .modal-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-titulo {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
        }

        .modal-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 18px;
        }

        .modal-meta-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 14px;
            color: #666;
        }

        .modal-generos {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .modal-sinopsis {
            font-size: 14px;
            line-height: 1.6;
            color: #666;
            margin: 16px 0;
        }

        .btn-reservar {
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-reservar:hover {
            opacity: 0.9;
        }

        .no-resultados {
            text-align: center;
            padding: 48px 24px;
            color: #666;
        }

        .no-resultados-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 sticky top-0 z-40 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <button onclick="window.history.back()" class="bg-gray-600 hover:bg-gray-700 px-3 py-2 rounded-lg transition text-sm">← Atrás</button>
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
        <!-- Filtros -->
        <div class="filtros-section">
            <div class="filtro-group">
                <input 
                    type="text" 
                    id="buscador"
                    placeholder="🔍 Buscar película..."
                    class="filtro-input"
                    onkeyup="buscarPelicula()"
                >
                <button onclick="limpiarFiltros()" class="btn-filtro bg-gray-500 hover:bg-gray-600">
                    ✕ Limpiar
                </button>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader mx-auto"></div>
            <p class="text-gray-600 mt-4">Cargando películas...</p>
        </div>

        <!-- Grid de Películas -->
        <div id="gridPeliculas" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>

        <!-- Sin Resultados -->
        <div id="noResultados" class="hidden no-resultados">
            <div class="no-resultados-icon">🎬</div>
            <h3 class="text-xl font-semibold mb-2 text-gray-800">No se encontraron películas</h3>
            <p>Intenta con otros términos de búsqueda o filtros</p>
        </div>
    </div>

    <!-- Modal Detalle -->
    <div id="modalDetalle" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitulo">Detalles de Película</h2>
                <button class="modal-close" onclick="cerrarModal()">✕</button>
            </div>
            <div class="modal-body">
                <div class="modal-detalle-grid">
                    <div class="modal-poster" id="modalPoster">🎬</div>
                    <div class="modal-info">
                        <h1 class="modal-titulo" id="detalleTitulo"></h1>
                        
                        <div class="modal-rating" id="detalleRating"></div>
                        
                        <div class="modal-meta-info">
                            <div><strong>⏱️ Duración:</strong> <span id="detalleDuracion">-</span> min</div>
                            <div><strong>📅 Lanzamiento:</strong> <span id="detalleFecha">-</span></div>
                            <div><strong>🌍 Idioma:</strong> <span id="detalleIdioma">-</span></div>
                        </div>

                        <div>
                            <strong class="block mb-2">Géneros:</strong>
                            <div class="modal-generos" id="detalleGeneros"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800 mb-2">Sinopsis</h3>
                    <p class="modal-sinopsis" id="detalleSinopsis"></p>
                </div>

                <div id="detalleExtendido" class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Votos</p>
                        <p class="text-xl font-bold text-blue-600" id="detalleVotos">-</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Presupuesto</p>
                        <p class="text-xl font-bold text-green-600" id="detallePresupuesto">-</p>
                    </div>
                </div>

                <button onclick="irAReservas()" class="btn-reservar">
                    🎟️ Reservar Entrada
                </button>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';
        let peliculasOriginal = [];
        let peliculasActuales = [];
        let generosDisponibles = [];

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

        // Cargar películas
        async function cargarPeliculas() {
            try {
                // Cargar películas de la BD local
                const response = await fetch(`${API_URL}/peliculas`);
                const data = await response.json();

                if (response.ok) {
                    peliculasOriginal = data.data || [];
                    peliculasActuales = peliculasOriginal;

                    mostrarPeliculas(peliculasActuales);
                } else {
                    mostrarError('Error al cargar películas');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarError('Error de conexión');
            }
        }

        // Mostrar películas
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
                    <div class="pelicula-card" onclick="abrirDetalle(${pelicula.id})">
                        <div class="pelicula-poster">
                            ${pelicula.url_imagen ? `<img src="${pelicula.url_imagen}" alt="${pelicula.titulo}">` : '🎬'}
                            ${pelicula.calificacion_tmdb ? `
                                <div class="rating-badge">
                                    ⭐ ${pelicula.calificacion_tmdb}
                                </div>
                            ` : ''}
                        </div>
                        <div class="pelicula-info">
                            <h3 class="pelicula-titulo">${pelicula.titulo}</h3>
                            <div class="pelicula-meta">
                                <span>⏱️ ${pelicula.duracion} min</span>
                                ${pelicula.calificacion_tmdb ? `<span>🗳️ ${pelicula.votos_tmdb?.toLocaleString() || 0}</span>` : ''}
                            </div>
                            <div class="pelicula-generos">
                                ${pelicula.generos?.slice(0, 2).map(g => `
                                    <span class="genero-tag">${g.nombre}</span>
                                `).join('') || ''}
                            </div>
                            <p class="pelicula-sinopsis">${pelicula.sinopsis || pelicula.descripcion || 'Sin descripción'}</p>
                            <button class="btn-ver-detalles">Ver Detalles →</button>
                        </div>
                    </div>
                `).join('');
                noResultados.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            loader.classList.add('hidden');
        }

        // Abrir modal detalle
        function abrirDetalle(id) {
            const pelicula = peliculasOriginal.find(p => p.id === id);
            if (!pelicula) return;

            document.getElementById('modalTitulo').textContent = pelicula.titulo;
            document.getElementById('detalleTitulo').textContent = pelicula.titulo;
            document.getElementById('detalleDuracion').textContent = pelicula.duracion || '-';
            document.getElementById('detalleFecha').textContent = pelicula.fecha_lanzamiento || '-';
            document.getElementById('detalleIdioma').textContent = pelicula.idioma_original || '-';
            document.getElementById('detalleSinopsis').textContent = pelicula.sinopsis || pelicula.descripcion || 'Sin sinopsis disponible';
            document.getElementById('detalleVotos').textContent = pelicula.votos?.toLocaleString() || '-';
            document.getElementById('detallePresupuesto').textContent = pelicula.presupuesto ? `$${(pelicula.presupuesto / 1000000).toFixed(1)}M` : '-';

            // Rating
            const ratingDiv = document.getElementById('detalleRating');
            if (pelicula.calificacion) {
                ratingDiv.innerHTML = `
                    <span style="font-size: 20px;">⭐ ${pelicula.calificacion}</span>
                    <span style="color: #666;">(${pelicula.votos?.toLocaleString() || 0} votos)</span>
                `;
            } else {
                ratingDiv.innerHTML = '<span style="color: #666;">Sin calificación</span>';
            }

            // Géneros - Mapear IDs de TMDB a nombres de géneros
            let generosTexto = 'Sin género';
            if (Array.isArray(pelicula.generos) && pelicula.generos.length > 0) {
                if (typeof pelicula.generos[0] === 'object') {
                    // Son objetos con nombre
                    generosTexto = pelicula.generos.map(g => `<span class="genero-tag">${g.nombre || g.name}</span>`).join('');
                } else {
                    // Son IDs, buscar en generosDisponibles
                    const generosNombres = pelicula.generos.map(gId => {
                        const genero = generosDisponibles.find(g => g.id === gId);
                        return genero ? genero.name : null;
                    }).filter(g => g);
                    generosTexto = generosNombres.map(g => `<span class="genero-tag">${g}</span>`).join('');
                }
            }
            document.getElementById('detalleGeneros').innerHTML = generosTexto;

            // Poster
            const poster = document.getElementById('modalPoster');
            if (pelicula.url_imagen || pelicula.poster_url) {
                poster.innerHTML = `<img src="${pelicula.url_imagen || pelicula.poster_url}" alt="${pelicula.titulo}">`;
            } else {
                poster.textContent = '🎬';
            }

            document.getElementById('modalDetalle').classList.add('show');
        }

        // Cerrar modal
        function cerrarModal() {
            document.getElementById('modalDetalle').classList.remove('show');
        }

        // Filtrar
        function filtrar() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();

            peliculasActuales = peliculasOriginal.filter(p => {
                return p.titulo.toLowerCase().includes(busqueda);
            });

            mostrarPeliculas(peliculasActuales);
        }

        // Buscar película
        function buscarPelicula() {
            filtrar();
        }

        // Limpiar filtros
        // Limpiar filtros
        function limpiarFiltros() {
            document.getElementById('buscador').value = '';
            peliculasActuales = peliculasOriginal;
            mostrarPeliculas(peliculasActuales);
        }

        // Ir a reservas
        function irAReservas() {
            window.location.href = '{{ route('reservas') }}';
        }

        // Ir a dashboard
        function irADashboard() {
            window.location.href = '{{ route('dashboard') }}';
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

        // Cerrar modal al hacer click afuera
        document.getElementById('modalDetalle').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            verificarAutenticacion();
            cargarPeliculas();
        });
    </script>
</body>
</html>
