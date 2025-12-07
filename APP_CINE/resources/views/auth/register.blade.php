<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CINE App</title>
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

        .register-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            display: flex;
        }

        .register-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 500px;
            width: 40%;
        }

        .register-right {
            padding: 60px 40px;
            width: 60%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .register-logo {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .register-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .register-subtitle {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
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

        .btn-register {
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
            margin-top: 20px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
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
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .register-left {
                width: 100%;
                min-height: auto;
                padding: 40px 30px;
            }

            .register-right {
                width: 100%;
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Panel Izquierdo -->
        <div class="register-left">
            <div class="register-logo">🎬</div>
            <div class="register-title">CINE App</div>
            <div class="register-subtitle">
                Crea una cuenta y comienza a disfrutar del mejor sistema de reserva de entradas para cine.
            </div>
        </div>

        <!-- Panel Derecho -->
        <div class="register-right">
            <h2 style="margin-bottom: 30px; color: #333; font-size: 28px;">Crear Cuenta</h2>
            
            <div id="errorMessage" class="error-message"></div>

            <form id="registerForm" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label for="name">Nombre Completo</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Tu nombre"
                        required
                    >
                </div>

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

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button type="submit" class="btn-register" id="btnRegister">Registrarse</button>
            </form>

            <div class="login-link">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';

        async function handleRegister(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            const errorMessage = document.getElementById('errorMessage');

            errorMessage.classList.remove('show');

            if (password !== password_confirmation) {
                showError('Las contraseñas no coinciden');
                return;
            }

            try {
                const response = await fetch(`${API_URL}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    })
                });

                const data = await response.json();

                if (response.ok && data.access_token) {
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));
                    window.location.href = '{{ route('dashboard') }}';
                } else {
                    showError(data.message || 'Error en el registro');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('Error de conexión');
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = '❌ ' + message;
            errorMessage.classList.add('show');
        }
    </script>
</body>
</html>
