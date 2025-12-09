<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géneros - CINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .genero-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .genero-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }

        .genero-icon {
            font-size: 48px;
            margin-bottom: 16px;
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
            height: 250px;
            object-fit: cover;
            background: #f0f0f0;
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
            color: #333;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pelicula-genero {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }

        .pelicula-duracion {
            font-size: 12px;
            color: #667eea;
            margin-bottom: 12px;
        }

        .btn-reservar {
            background-color: #667eea;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-top: auto;
        }

        .btn-reservar:hover {
            background-color: #5568d3;
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
                <a href="/generos" class="active">Géneros</a>
                <a href="/funciones">Funciones</a>
                <a href="/salas">Salas</a>
                <a href="/reservas">Mis Reservas</a>
                <a href="/perfil">Mi Perfil</a>
                <a href="/admin/peliculas" id="adminLink" style="display:none;">Gestión Admin</a>
                <a href="#" onclick="cerrarSesion()">Salir</a>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto p-4">
        <!-- Encabezado -->
        <div class="mb-8 mt-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">🎞️ Géneros de Películas</h2>
            <p class="text-gray-600">Explora películas por género y encuentra tu favorita</p>
        </div>

        <!-- Géneros Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12" id="generosContainer">
            <div class="flex justify-center items-center h-64">
                <div class="text-center">
                    <div style="border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p class="text-gray-600 mt-4">Cargando géneros...</p>
                </div>
            </div>
        </div>

        <!-- Películas por género seleccionado -->
        <div id="generoPeliculasSection" style="display:none;">
            <h3 class="text-2xl font-bold text-gray-800 mb-6" id="generoSeleccionadoTitulo"></h3>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6" id="peliculasGeneroContainer"></div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        let generoSeleccionado = null;

        // Verificar autenticación
        if (!token) {
            window.location.href = '/login';
        }

        // Mostrar link de admin si el usuario es admin
        async function verificarAdmin() {
            try {
                const response = await fetch('/api/user', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (response.ok) {
                    const user = await response.json();
                    if (user.role === 'admin') {
                        document.getElementById('adminLink').style.display = 'inline-block';
                    }
                }
            } catch (error) {
                console.error('Error al verificar admin:', error);
            }
        }

        // Cargar géneros
        async function cargarGeneros() {
            try {
                const response = await fetch('/api/generos', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar géneros');
                
                const generos = await response.json();
                mostrarGeneros(generos);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('generosContainer').innerHTML = 
                    '<p class="text-red-500">Error al cargar los géneros</p>';
            }
        }

        function mostrarGeneros(generos) {
            const container = document.getElementById('generosContainer');
            
            if (generos.length === 0) {
                container.innerHTML = '<p class="text-gray-600">No hay géneros disponibles</p>';
                return;
            }

            const iconos = {
                'Acción': '⚔️',
                'Comedia': '😂',
                'Drama': '💔',
                'Terror': '👻',
                'Ciencia Ficción': '🚀',
                'Romance': '💕',
                'Animación': '🎨',
                'Thriller': '🔪',
                'Aventura': '🗺️',
                'Fantasía': '✨'
            };

            container.innerHTML = generos.map(genero => `
                <div class="genero-card" onclick="cargarPeliculasPorGenero(${genero.id}, '${genero.nombre}')">
                    <div class="genero-icon">${iconos[genero.nombre] || '🎬'}</div>
                    <h3 class="text-2xl font-bold">${genero.nombre}</h3>
                    <p class="text-sm text-white/80 mt-2">${genero.peliculas_count || 0} películas</p>
                </div>
            `).join('');
        }

        async function cargarPeliculasPorGenero(generoId, nombreGenero) {
            generoSeleccionado = { id: generoId, nombre: nombreGenero };
            
            try {
                const response = await fetch(`/api/generos/${generoId}/peliculas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar películas');
                
                const peliculas = await response.json();
                mostrarPeliculasGenero(peliculas, nombreGenero);
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar películas del género');
            }
        }

        function mostrarPeliculasGenero(peliculas, nombreGenero) {
            const section = document.getElementById('generoPeliculasSection');
            const titulo = document.getElementById('generoSeleccionadoTitulo');
            const container = document.getElementById('peliculasGeneroContainer');

            titulo.textContent = `${nombreGenero} (${peliculas.length} películas)`;
            
            if (peliculas.length === 0) {
                container.innerHTML = '<p class="text-gray-600">No hay películas en este género</p>';
                section.style.display = 'block';
                return;
            }

            container.innerHTML = peliculas.map(pelicula => `
                <div class="pelicula-card fade-in">
                    <img src="${pelicula.url_imagen || 'https://via.placeholder.com/300x400?text=Sin+Imagen'}" 
                         alt="${pelicula.titulo}" class="pelicula-poster">
                    <div class="pelicula-info">
                        <div class="pelicula-titulo" title="${pelicula.titulo}">${pelicula.titulo}</div>
                        <div class="pelicula-genero">${pelicula.generos?.map(g => g.nombre).join(', ') || 'Sin género'}</div>
                        <div class="pelicula-duracion">⏱️ ${pelicula.duracion} min</div>
                        <button class="btn-reservar" onclick="irAReservar(${pelicula.id}, '${pelicula.titulo}')">
                            Reservar
                        </button>
                    </div>
                </div>
            `).join('');
            
            section.style.display = 'block';
        }

        function irAReservar(peliculaId, titulo) {
            localStorage.setItem('peliculaSeleccionada', JSON.stringify({ id: peliculaId, titulo }));
            window.location.href = '/reservas';
        }

        function cerrarSesion() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }

        // Cargar datos al iniciar
        window.addEventListener('load', () => {
            verificarAdmin();
            cargarGeneros();
        });
    </script>
</body>
</html>
