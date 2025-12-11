<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas - CINE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .sala-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-top: 5px solid #667eea;
        }

        .sala-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }

        .sala-nombre {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 16px;
        }

        .sala-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }

        .info-label {
            color: #666;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .info-valor {
            color: #333;
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .asientos-preview {
            background: #f0f0f0;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            text-align: center;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .asiento-mini {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin: 2px;
            background-color: #4ade80;
            border-radius: 2px;
        }

        .caracteristicas {
            list-style: none;
            padding: 0;
            margin: 16px 0;
        }

        .caracteristicas li {
            padding: 8px 0;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-funciones {
            background-color: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn-funciones:hover {
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
                <a href="/generos">Géneros</a>
                <a href="/funciones">Funciones</a>
                <a href="/salas" class="active">Salas</a>
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
            <h2 class="text-4xl font-bold text-gray-800 mb-4">🎪 Salas del Cine</h2>
            <p class="text-gray-600">Conoce los detalles y especificaciones de nuestras salas</p>
        </div>

        <!-- Grid de salas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="salasContainer">
            <div class="flex justify-center items-center h-64">
                <div class="text-center">
                    <div style="border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
                    <p class="text-gray-600 mt-4">Cargando salas...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('auth_token');

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

        // Cargar salas
        async function cargarSalas() {
            try {
                const response = await fetch('/api/salas', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (!response.ok) throw new Error('Error al cargar salas');
                
                const salas = await response.json();
                mostrarSalas(salas);
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('salasContainer').innerHTML = 
                    '<p class="text-red-500">Error al cargar las salas</p>';
            }
        }

        function mostrarSalas(salas) {
            const container = document.getElementById('salasContainer');
            
            if (salas.length === 0) {
                container.innerHTML = '<p class="text-gray-600">No hay salas disponibles</p>';
                return;
            }

            container.innerHTML = salas.map(sala => {
                const asientosDisponibles = (sala.capacidad - (sala.asientos_ocupados || 0));
                const porcentajeOcupado = Math.round(((sala.asientos_ocupados || 0) / sala.capacidad) * 100);
                
                return `
                    <div class="sala-card fade-in">
                        <div class="sala-nombre">${sala.nombre}</div>
                        
                        <div class="sala-info">
                            <div class="info-item">
                                <div class="info-label">💺 Capacidad</div>
                                <div class="info-valor">${sala.capacidad}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">✅ Disponibles</div>
                                <div class="info-valor">${asientosDisponibles}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">❌ Ocupados</div>
                                <div class="info-valor">${sala.asientos_ocupados || 0}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">📊 Ocupación</div>
                                <div class="info-valor">${porcentajeOcupado}%</div>
                            </div>
                        </div>

                        <div class="asientos-preview">
                            <div style="margin-bottom: 8px; font-size: 12px; color: #666;">Asientos disponibles</div>
                            ${Array(Math.min(sala.capacidad, 20)).fill().map(() => '<div class="asiento-mini"></div>').join('')}
                        </div>

                        <ul class="caracteristicas">
                            <li>🎬 ${sala.tipo_pantalla || 'Pantalla estándar'}</li>
                            <li>🔊 Sonido surround 5.1</li>
                            <li>♿ Accesible para personas con discapacidad</li>
                            <li>🧒 Apto para menores de edad</li>
                        </ul>

                        <button class="btn-funciones" onclick="verFuncionesSala(${sala.id}, '${sala.nombre}')">
                            Ver funciones en ${sala.nombre}
                        </button>
                    </div>
                `;
            }).join('');
        }

        function verFuncionesSala(salaId, salaNombre) {
            localStorage.setItem('salaSeleccionada', JSON.stringify({ id: salaId, nombre: salaNombre }));
            window.location.href = '/funciones';
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
            cargarSalas();
        });
    </script>
</body>
</html>
