<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funciones - CINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .funcion-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 5px solid #667eea;
        }

        .funcion-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }

        .funcion-titulo {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .funcion-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin: 16px 0;
        }

        .info-item {
            font-size: 14px;
        }

        .info-label {
            color: #999;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .info-valor {
            color: #333;
            font-size: 16px;
            font-weight: 500;
        }

        .estado-disponible {
            background-color: #4ade80;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .estado-completo {
            background-color: #ef4444;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .btn-reservar {
            background-color: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%;
            margin-top: 16px;
        }

        .btn-reservar:hover:not(:disabled) {
            background-color: #5568d3;
        }

        .btn-reservar:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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

        .filtros {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .filtro-grupo {
            display: flex;
            flex-direction: column;
        }

        .filtro-grupo label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .filtro-grupo select,
        .filtro-grupo input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
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
                <a href="/generos">Géneros</a>
                <a href="/funciones" class="active">Funciones</a>
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
            <h2 class="text-4xl font-bold text-gray-800 mb-4">🕐 Funciones Disponibles</h2>
            <p class="text-gray-600">Consulta los horarios disponibles para cada película</p>
        </div>

        <!-- Filtros -->
        <div class="filtros">
            <div class="filtro-grupo">
                <label>Película:</label>
                <select id="filtroJelícula" onchange="filtrarFunciones()">
                    <option value="">Todas las películas</option>
                </select>
            </div>
            <div class="filtro-grupo">
                <label>Sala:</label>
                <select id="filtroSala" onchange="filtrarFunciones()">
                    <option value="">Todas las salas</option>
                </select>
            </div>
            <div class="filtro-grupo">
                <label>Fecha:</label>
                <input type="date" id="filtroFecha" onchange="filtrarFunciones()">
            </div>
            <div class="filtro-grupo">
                <label>&nbsp;</label>
                <button onclick="limpiarFiltros()" class="bg-gray-400 text-white px-4 py-2 rounded-6 hover:bg-gray-500">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <!-- Contenedor de funciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="funcionesContainer">
            <div class="flex justify-center items-center h-64">
                <div class="text-center">
                    <div style="border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p class="text-gray-600 mt-4">Cargando funciones...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');
        let todasLasFunciones = [];

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
                    if (user.role === 'admin') {
                        document.getElementById('adminLink').style.display = 'inline-block';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Cargar funciones
        async function cargarFunciones() {
            try {
                const response = await fetch('/api/funciones', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar funciones');
                
                todasLasFunciones = await response.json();
                llenarFiltros();
                mostrarFunciones(todasLasFunciones);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('funcionesContainer').innerHTML = 
                    '<p class="text-red-500">Error al cargar las funciones</p>';
            }
        }

        function llenarFiltros() {
            // Llenar películas
            const peliculas = [...new Set(todasLasFunciones.map(f => f.pelicula).filter(Boolean))];
            const selectPeliculas = document.getElementById('filtroJelícula');
            peliculas.forEach(pelicula => {
                const option = document.createElement('option');
                option.value = pelicula.id;
                option.textContent = pelicula.titulo;
                selectPeliculas.appendChild(option);
            });

            // Llenar salas
            const salas = [...new Set(todasLasFunciones.map(f => f.sala).filter(Boolean))];
            const selectSalas = document.getElementById('filtroSala');
            salas.forEach(sala => {
                const option = document.createElement('option');
                option.value = sala.id;
                option.textContent = `${sala.nombre} (${sala.capacidad} asientos)`;
                selectSalas.appendChild(option);
            });
        }

        function mostrarFunciones(funciones) {
            const container = document.getElementById('funcionesContainer');
            
            if (funciones.length === 0) {
                container.innerHTML = '<p class="text-gray-600 col-span-full text-center py-8">No hay funciones disponibles</p>';
                return;
            }

            container.innerHTML = funciones.map(funcion => {
                const disponible = funcion.asientos_disponibles > 0;
                const fecha = new Date(funcion.fecha_hora).toLocaleDateString('es-ES');
                const hora = new Date(funcion.fecha_hora).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                
                return `
                    <div class="funcion-card fade-in">
                        <div class="funcion-titulo">${funcion.pelicula?.titulo || 'Película'}</div>
                        
                        <div class="funcion-info">
                            <div class="info-item">
                                <div class="info-label">📅 Fecha</div>
                                <div class="info-valor">${fecha}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">🕐 Hora</div>
                                <div class="info-valor">${hora}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">🎪 Sala</div>
                                <div class="info-valor">${funcion.sala?.nombre || 'Sin sala'}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">💺 Asientos disponibles</div>
                                <div class="info-valor">${funcion.asientos_disponibles || 0}/${funcion.sala?.capacidad || 0}</div>
                            </div>
                        </div>
                        
                        <div>
                            ${disponible ? '<span class="estado-disponible">✅ Disponible</span>' : '<span class="estado-completo">❌ Completo</span>'}
                        </div>
                        
                        <button class="btn-reservar" 
                                onclick="irAReservar(${funcion.pelicula?.id})"
                                ${!disponible ? 'disabled' : ''}>
                            ${disponible ? 'Reservar ahora' : 'Sin disponibilidad'}
                        </button>
                    </div>
                `;
            }).join('');
        }

        function filtrarFunciones() {
            const peliculaId = document.getElementById('filtroJelícula').value;
            const salaId = document.getElementById('filtroSala').value;
            const fecha = document.getElementById('filtroFecha').value;

            let funcionesFiltradas = todasLasFunciones;

            if (peliculaId) {
                funcionesFiltradas = funcionesFiltradas.filter(f => f.pelicula_id === parseInt(peliculaId));
            }

            if (salaId) {
                funcionesFiltradas = funcionesFiltradas.filter(f => f.sala_id === parseInt(salaId));
            }

            if (fecha) {
                funcionesFiltradas = funcionesFiltradas.filter(f => {
                    const fechaFuncion = new Date(f.fecha_hora).toISOString().split('T')[0];
                    return fechaFuncion === fecha;
                });
            }

            mostrarFunciones(funcionesFiltradas);
        }

        function limpiarFiltros() {
            document.getElementById('filtroJelícula').value = '';
            document.getElementById('filtroSala').value = '';
            document.getElementById('filtroFecha').value = '';
            mostrarFunciones(todasLasFunciones);
        }

        function irAReservar(peliculaId) {
            localStorage.setItem('peliculaSeleccionada', JSON.stringify({ id: peliculaId }));
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
            cargarFunciones();

            // Establecer fecha mínima a hoy
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('filtroFecha').min = hoy;
        });
    </script>
</body>
</html>
