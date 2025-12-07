<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CINE App</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 500px;
            width: 40%;
        }

        .login-right {
            padding: 60px 40px;
            width: 60%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-logo {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .login-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .login-subtitle {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            border-left: 4px solid #c33;
        }

        .error-message.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            border-left: 4px solid #3c3;
        }

        .success-message.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        .btn-login.loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login.loading .loading-spinner {
            display: block;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .remember-forgot a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .remember-forgot a:hover {
            color: #764ba2;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left {
                width: 100%;
                min-height: auto;
                padding: 40px 30px;
            }

            .login-right {
                width: 100%;
                padding: 40px 30px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-subtitle {
                font-size: 14px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Panel Izquierdo -->
        <div class="login-left">
            <div class="login-logo">🎬</div>
            <div class="login-title">CINE App</div>
            <div class="login-subtitle">
                Sistema de reserva de entradas para cine. Accede a tu cuenta y comienza a reservar tus películas favoritas.
            </div>
            <div style="opacity: 0.8; font-size: 14px; line-height: 1.8;">
                <div style="margin-bottom: 15px;">
                    <strong>Credenciales de Prueba:</strong>
                </div>
                <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 6px; font-family: monospace;">
                    Admin:<br>
                    📧 admin@cine.com<br>
                    🔐 admin123<br><br>
                    Cliente:<br>
                    📧 cliente@cine.com<br>
                    🔐 cliente123
                </div>
            </div>
        </div>

        <!-- Panel Derecho -->
        <div class="login-right">
            <h2 style="margin-bottom: 30px; color: #333; font-size: 28px;">Bienvenido</h2>
            
            <div id="errorMessage" class="error-message"></div>
            <div id="successMessage" class="success-message"></div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="tu@email.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="remember-forgot">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" style="margin: 0; font-weight: normal;">Recuérdame</label>
                    </div>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <span class="loading-spinner"></span>
                    <span id="btnText">Iniciar Sesión</span>
                </button>
            </form>

            <div class="signup-link">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Registrarse aquí</a>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';

        async function handleLogin(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btnLogin = document.getElementById('btnLogin');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            // Limpiar mensajes
            errorMessage.classList.remove('show');
            successMessage.classList.remove('show');

            // Validar campos
            if (!email || !password) {
                showError('Por favor completa todos los campos');
                return;
            }

            // Mostrar loading
            btnLogin.disabled = true;
            btnLogin.classList.add('loading');

            try {
                const response = await fetch(`${API_URL}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok && data.access_token) {
                    // Guardar token
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));

                    // Mostrar éxito
                    showSuccess(`¡Bienvenido ${data.user.name}!`);

                    // Redirigir después de 1.5 segundos
                    setTimeout(() => {
                        window.location.href = '{{ route('dashboard') }}';
                    }, 1500);
                } else {
                    showError(data.message || 'Credenciales inválidas. Verifica tu email y contraseña.');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Error de conexión. Verifica que el servidor esté corriendo.');
            } finally {
                btnLogin.disabled = false;
                btnLogin.classList.remove('loading');
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = '❌ ' + message;
            errorMessage.classList.add('show');

            setTimeout(() => {
                errorMessage.classList.remove('show');
            }, 5000);
        }

        function showSuccess(message) {
            const successMessage = document.getElementById('successMessage');
            successMessage.textContent = '✅ ' + message;
            successMessage.classList.add('show');
        }

        // Auto-llenar campos de prueba
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                window.location.href = '{{ route('dashboard') }}';
            }
        });

        // Permitir Enter en el password
        document.getElementById('password').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>
