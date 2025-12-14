<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CINE App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎬</span>
                <h1 class="text-xl font-bold">CINE Admin Panel</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="/" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg transition flex items-center gap-2">
                    ← Atrás
                </a>
                <span id="userName" class="text-sm">Admin</span>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Panel de Administración</h2>

        <!-- Grid de opciones de administración -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Gestión de Películas -->
            <div class="card-hover bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-600 cursor-pointer" onclick="window.location.href='/admin/peliculas'">
                <div class="text-4xl mb-4">🎥</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Películas</h3>
                <p class="text-gray-600 text-sm">Administra el catálogo de películas</p>
                <div class="mt-4 flex items-center text-blue-600">
                    <span class="text-sm font-semibold">Ir a Gestión</span>
                    <span class="ml-2">→</span>
                </div>
            </div>

            <!-- Gestión de Géneros -->
            <div class="card-hover bg-white p-6 rounded-lg shadow-md border-l-4 border-green-600 cursor-pointer" onclick="window.location.href='/admin/generos'">
                <div class="text-4xl mb-4">🎭</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Géneros</h3>
                <p class="text-gray-600 text-sm">Administra los géneros de películas</p>
                <div class="mt-4 flex items-center text-green-600">
                    <span class="text-sm font-semibold">Ir a Gestión</span>
                    <span class="ml-2">→</span>
                </div>
            </div>

            <!-- Gestión de Salas -->
            <div class="card-hover bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-600 cursor-pointer" onclick="window.location.href='/admin/salas'">
                <div class="text-4xl mb-4">🎞️</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Salas</h3>
                <p class="text-gray-600 text-sm">Administra las salas de cine</p>
                <div class="mt-4 flex items-center text-purple-600">
                    <span class="text-sm font-semibold">Ir a Gestión</span>
                    <span class="ml-2">→</span>
                </div>
            </div>

            <!-- Gestión de Funciones -->
            <div class="card-hover bg-white p-6 rounded-lg shadow-md border-l-4 border-orange-600 cursor-pointer" onclick="window.location.href='/admin/funciones'">
                <div class="text-4xl mb-4">⏰</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Funciones</h3>
                <p class="text-gray-600 text-sm">Administra las funciones de cine</p>
                <div class="mt-4 flex items-center text-orange-600">
                    <span class="text-sm font-semibold">Ir a Gestión</span>
                    <span class="ml-2">→</span>
                </div>
            </div>

            <!-- Gestión de Reservas -->
            <div class="card-hover bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-600 cursor-pointer" onclick="window.location.href='/admin/reservas'">
                <div class="text-4xl mb-4">🎫</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Reservas</h3>
                <p class="text-gray-600 text-sm">Gestiona las reservas de clientes</p>
                <div class="mt-4 flex items-center text-purple-600">
                    <span class="text-sm font-semibold">Ir a Gestión</span>
                    <span class="ml-2">→</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Obtener datos del token
        function obtenerDatos() {
            // Intentar obtener token de múltiples locales
            const token = localStorage.getItem('auth_token') || 
                         localStorage.getItem('access_token') ||
                         sessionStorage.getItem('auth_token');
            
            // Intentar obtener user data de múltiples locales
            let userStr = localStorage.getItem('user_data') || 
                         localStorage.getItem('user') ||
                         sessionStorage.getItem('user_data');
            
            let user = {};
            if (userStr) {
                try {
                    user = JSON.parse(userStr);
                } catch (e) {
                    console.error('Error parsing user data:', e);
                    // Intentar obtener solo el nombre
                    user = { name: 'Administrador', role: 'admin' };
                }
            }

            if (!token) {
                console.log('No token found');
                alert('Sesión expirada. Por favor inicia sesión de nuevo.');
                window.location.href = '/login';
                return;
            }

            // Mostrar nombre del usuario
            document.getElementById('userName').textContent = user.name || 'Administrador';
        }

        // Cerrar sesión
        function handleLogout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('access_token');
            localStorage.removeItem('user');
            localStorage.removeItem('user_data');
            localStorage.removeItem('token_type');
            sessionStorage.clear();
            window.location.href = '/login';
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', obtenerDatos);
    </script>
</body>
</html>
