<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CINE App - Catálogo de Películas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
                color: white;
                font-family: 'Figtree', sans-serif;
                min-height: 100vh;
            }
            .navbar {
                background: rgba(15, 23, 42, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 50;
            }
            .logo {
                font-size: 2rem;
                font-weight: bold;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .nav-buttons a {
                display: inline-block;
                margin-left: 1rem;
                padding: 0.6rem 1.5rem;
                background: #f97316;
                color: white;
                text-decoration: none;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.3s;
            }
            .nav-buttons a:hover {
                background: #ea580c;
                transform: translateY(-2px);
            }
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 2rem;
            }
            .header-section {
                margin: 3rem 0;
                text-align: left;
            }
            .header-section h1 {
                font-size: 3.5rem;
                font-weight: 900;
                margin-bottom: 0.5rem;
                background: linear-gradient(90deg, #fbbf24 0%, #f97316 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .header-section p {
                font-size: 1.25rem;
                color: #cbd5e1;
            }
            .search-section {
                margin: 2rem 0;
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }
            .search-section input {
                flex: 1;
                min-width: 300px;
                padding: 0.75rem 1rem;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 0.5rem;
                color: white;
                font-size: 1rem;
            }
            .search-section input::placeholder {
                color: rgba(255, 255, 255, 0.6);
            }
            .search-section input:focus {
                outline: none;
                border-color: #f97316;
                background: rgba(255, 255, 255, 0.15);
            }
            .search-section button {
                padding: 0.75rem 1.5rem;
                background: #64748b;
                color: white;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s;
            }
            .search-section button:hover {
                background: #475569;
            }
            .movies-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1.5rem;
                margin: 2rem 0;
            }
            .movie-card {
                background: rgba(30, 41, 59, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                position: relative;
            }
            .movie-card:hover {
                transform: translateY(-8px);
                border-color: #f97316;
                box-shadow: 0 20px 40px rgba(249, 115, 22, 0.3);
            }
            .movie-poster {
                width: 100%;
                aspect-ratio: 9/13;
                background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 4rem;
                position: relative;
                overflow: hidden;
            }
            .movie-poster::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0);
                transition: background 0.3s;
            }
            .movie-card:hover .movie-poster::after {
                background: rgba(0, 0, 0, 0.3);
            }
            .movie-info {
                padding: 1rem;
            }
            .movie-title {
                font-weight: bold;
                font-size: 1rem;
                margin-bottom: 0.5rem;
                line-height: 1.4;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .movie-meta {
                font-size: 0.875rem;
                color: #cbd5e1;
                margin-bottom: 0.75rem;
            }
            .movie-genres {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }
            .genre-tag {
                background: #f97316;
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            .movie-btn {
                width: 100%;
                padding: 0.75rem;
                background: linear-gradient(90deg, #f97316 0%, #ea580c 100%);
                color: white;
                border: none;
                border-radius: 0.5rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
            }
            .movie-btn:hover {
                transform: scale(1.02);
                box-shadow: 0 5px 15px rgba(249, 115, 22, 0.4);
            }
            .loading {
                text-align: center;
                padding: 3rem;
                font-size: 1.25rem;
            }
            .no-results {
                text-align: center;
                padding: 3rem;
                color: #cbd5e1;
                font-size: 1.25rem;
            }
            .result-count {
                color: #fbbf24;
                font-weight: 600;
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <div class="navbar">
            <div class="logo">
                🎬 CINE App
            </div>
            <div class="nav-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Inicia Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" style="background: #64748b;">Regístrate</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <!-- Header Section -->
            <div class="header-section">
                <h1>Películas en Cartelera</h1>
                <p>Explora y reserva tus películas favoritas</p>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <input 
                    type="text" 
                    id="buscador"
                    placeholder="🔍 Busca tu película..."
                    onkeyup="buscarPelicula()"
                >
                <button onclick="limpiarFiltros()">Limpiar</button>
            </div>

            <!-- Result Counter -->
            <div id="contadorResultados" class="result-count"></div>

            <!-- Loading -->
            <div id="loader" class="loading">
                <p>⏳ Cargando películas...</p>
            </div>

            <!-- Movies Grid -->
            <div id="gridPeliculas" class="movies-grid" style="display: none;"></div>

            <!-- No Results -->
            <div id="noResultados" class="no-results" style="display: none;">
                No se encontraron películas
            </div>
        </div>

        <script>
            const API_URL = 'http://127.0.0.1:8000/api';
            let peliculasOriginal = [];
            let peliculasActuales = [];

            document.addEventListener('DOMContentLoaded', () => {
                cargarPeliculas();
            });

            async function cargarPeliculas() {
                try {
                    const response = await fetch(`${API_URL}/peliculas`);
                    const data = await response.json();

                    if (response.ok) {
                        peliculasOriginal = data.data || data;
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

            function mostrarPeliculas(peliculas) {
                const grid = document.getElementById('gridPeliculas');
                const noResultados = document.getElementById('noResultados');
                const loader = document.getElementById('loader');
                const contador = document.getElementById('contadorResultados');

                if (peliculas.length === 0) {
                    grid.style.display = 'none';
                    noResultados.style.display = 'block';
                    loader.style.display = 'none';
                    contador.textContent = '';
                    return;
                }

                contador.textContent = `📽️ ${peliculas.length} película${peliculas.length !== 1 ? 's' : ''} encontrada${peliculas.length !== 1 ? 's' : ''}`;

                grid.innerHTML = peliculas.map(pelicula => {
                    const generos = pelicula.generos && pelicula.generos.length > 0 
                        ? pelicula.generos.slice(0, 2).map(g => g.nombre)
                        : ['Sin género'];

                    return `
                        <div class="movie-card" onclick="redirigirALogin()">
                            <div class="movie-poster">
                                🎬
                            </div>
                            <div class="movie-info">
                                <div class="movie-title">${pelicula.titulo}</div>
                                <div class="movie-meta">⏱️ ${pelicula.duracion} min</div>
                                <div class="movie-genres">
                                    ${generos.map(g => `<span class="genre-tag">${g}</span>`).join('')}
                                </div>
                                <button class="movie-btn" onclick="event.stopPropagation(); redirigirALogin()">
                                    🎫 Reservar
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');

                grid.style.display = 'grid';
                noResultados.style.display = 'none';
                loader.style.display = 'none';
            }

            function mostrarError(mensaje) {
                document.getElementById('loader').innerHTML = `<p style="color: #ef4444;">${mensaje}</p>`;
            }

            function buscarPelicula() {
                const busqueda = document.getElementById('buscador').value.toLowerCase();

                peliculasActuales = peliculasOriginal.filter(p => {
                    return p.titulo.toLowerCase().includes(busqueda);
                });

                mostrarPeliculas(peliculasActuales);
            }

            function limpiarFiltros() {
                document.getElementById('buscador').value = '';
                peliculasActuales = peliculasOriginal;
                mostrarPeliculas(peliculasActuales);
            }

            function redirigirALogin() {
                window.location.href = "{{ route('login') }}";
            }
        </script>
    </body>
</html>
