<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CINE App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .welcome-section h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .menu-card-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .menu-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .menu-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
            🎬 CINE App
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar" id="userInitial">U</div>
                <div>
                    <div id="userName" style="font-weight: 600;">Usuario</div>
                    <div id="userEmail" style="font-size: 12px; opacity: 0.8;">correo@email.com</div>
                </div>
            </div>
            <button class="btn-logout" onclick="handleLogout()">Cerrar Sesión</button>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="container">
        <div class="welcome-section">
            <h1>Bienvenido a CINE App 🎬</h1>
            <p>Selecciona una opción a continuación para comenzar:</p>
        </div>

        <div class="menu-grid">
            <a href="#" onclick="handleNavigation(event, 'movies')" class="menu-card">
                <div class="menu-card-icon">🎥</div>
                <h3>Películas</h3>
                <p>Explora el catálogo de películas disponibles</p>
            </a>

            <a href="#" onclick="handleNavigation(event, 'reservations')" class="menu-card">
                <div class="menu-card-icon">🎟️</div>
                <h3>Mis Reservas</h3>
                <p>Gestiona tus reservas y entradas</p>
            </a>

            <a href="#" onclick="handleNavigation(event, 'showtimes')" class="menu-card">
                <div class="menu-card-icon">⏰</div>
                <h3>Funciones</h3>
                <p>Ver las funciones disponibles</p>
            </a>

            <a href="#" onclick="handleNavigation(event, 'rooms')" class="menu-card">
                <div class="menu-card-icon">🎭</div>
                <h3>Salas</h3>
                <p>Consulta las salas del cine</p>
            </a>

            <a href="#" onclick="handleNavigation(event, 'genres')" class="menu-card">
                <div class="menu-card-icon">🎞️</div>
                <h3>Géneros</h3>
                <p>Filtra películas por género</p>
            </a>

            <a href="#" onclick="handleNavigation(event, 'profile')" class="menu-card">
                <div class="menu-card-icon">👤</div>
                <h3>Mi Perfil</h3>
                <p>Actualiza tu información personal</p>
            </a>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';

        // Verificar autenticación y cargar datos del usuario
        function checkAuth() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            loadUserData();
        }

        function loadUserData() {
            const userData = localStorage.getItem('user_data');
            if (userData) {
                try {
                    const user = JSON.parse(userData);
                    document.getElementById('userName').textContent = user.name || 'Usuario';
                    document.getElementById('userEmail').textContent = user.email || 'correo@email.com';
                    
                    const initials = user.name ? user.name.split(' ').map(n => n[0]).join('').toUpperCase() : 'U';
                    document.getElementById('userInitial').textContent = initials;
                } catch (error) {
                    console.error('Error parsing user data:', error);
                }
            }
        }

        function handleLogout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                const token = localStorage.getItem('auth_token');
                
                fetch(`${API_URL}/auth/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_data');
                    window.location.href = '{{ route('login') }}';
                }).catch(error => {
                    console.error('Error:', error);
                    // Logout de todas formas
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user_data');
                    window.location.href = '{{ route('login') }}';
                });
            }
        }

        function handleNavigation(event, section) {
            event.preventDefault();
            alert(`Sección "${section}" en desarrollo. Próximamente disponible.`);
        }

        // Verificar autenticación al cargar la página
        document.addEventListener('DOMContentLoaded', checkAuth);
    </script>
</body>
</html>
