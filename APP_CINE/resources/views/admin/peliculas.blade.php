<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Películas - CINE Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-600 to-purple-700 text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🎬</span>
                <h1 class="text-xl font-bold">CINE Admin - Gestión de Películas</h1>
            </div>
            <div class="flex items-center gap-4">
                <span id="userName" class="text-sm">Admin</span>
                <button onclick="handleLogout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Encabezado con botones -->
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <h2 class="text-3xl font-bold text-gray-800">Películas</h2>
            <div class="flex gap-3 flex-wrap">
                <a href="/admin/reservas" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md">
                    📋 Gestionar Reservas
                </a>
                <button onclick="abrirFormularioCrear()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md">
                    + Nueva Película
                </button>
            </div>
        </div>

        <!-- Buscador -->
        <div class="mb-6">
            <input 
                type="text" 
                id="buscador"
                placeholder="Buscar películas por título..."
                onkeyup="filtrarPeliculas()"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
            >
        </div>

        <!-- Mensaje de error -->
        <div id="errorMessage" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded fade-in">
            <p id="errorText"></p>
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center py-12">
            <div class="loader"></div>
            <p class="text-gray-600 mt-4">Cargando películas...</p>
        </div>

        <!-- Tabla de películas -->
        <div id="contenidoPeliculas" class="hidden fade-in">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Título</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Género</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Duración</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Estado</th>
                            <th class="px-6 py-4 text-left text-gray-700 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody" class="divide-y divide-gray-200">
                        <!-- Se llena con JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Mensaje si no hay películas -->
            <div id="noResultados" class="hidden text-center py-12">
                <p class="text-gray-500 text-lg">No se encontraron películas</p>
            </div>
        </div>
    </div>

    <!-- Modal Crear/Editar Película -->
    <div id="modalFormulario" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto fade-in">
            <div class="sticky top-0 bg-gray-100 border-b px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitulo" class="text-xl font-bold text-gray-800">Nueva Película</h3>
                <button onclick="cerrarFormulario()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form id="formularioPelicula" onsubmit="guardarPelicula(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Título *</label>
                    <input 
                        type="text" 
                        id="titulo"
                        required
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="Ej: Avatar"
                    >
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Sinopsis *</label>
                    <textarea 
                        id="sinopsis"
                        required
                        rows="4"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="Descripción de la película..."
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Duración (minutos) *</label>
                        <input 
                            type="number" 
                            id="duracion"
                            required
                            min="1"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                            placeholder="120"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Género *</label>
                        <select 
                            id="genero_id"
                            required
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        >
                            <option value="">Seleccionar género...</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Imagen de Película</label>
                    <div class="mb-3">
                        <input 
                            type="file" 
                            id="imagenFile"
                            accept="image/*"
                            class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        >
                        <p class="text-sm text-gray-500 mt-2">Formatos: JPEG, PNG, GIF, WebP (máx. 5MB)</p>
                    </div>
                    <button 
                        type="button" 
                        id="btnSubirImagen"
                        onclick="subirImagen()"
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition disabled:opacity-50"
                        disabled
                    >
                        📤 Subir Imagen
                    </button>
                    <p id="estadoUpload" class="text-sm text-gray-600 mt-2"></p>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">O ingresa URL manual de imagen</label>
                    <input 
                        type="url" 
                        id="url_imagen"
                        class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600"
                        placeholder="https://ejemplo.com/imagen.jpg"
                    >
                </div>

                <div id="previewImagen" class="hidden mt-4">
                    <label class="block text-gray-700 font-semibold mb-2">Vista Previa</label>
                    <img id="imagenPreview" src="" alt="Vista previa" class="max-w-xs rounded-lg border-2 border-gray-300">
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <p id="estadoFormulario" class="text-sm text-gray-600"></p>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="button" 
                        onclick="cerrarFormulario()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        id="btnGuardar"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Confirmar Eliminar -->
    <div id="modalEliminar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-sm mx-4 fade-in">
            <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                <h3 class="text-xl font-bold text-red-800">Confirmar eliminación</h3>
            </div>
            <div class="p-6">
                <p id="textoConfirmacion" class="text-gray-700 mb-6"></p>
                <div class="flex gap-4">
                    <button 
                        onclick="cerrarModalEliminar()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        onclick="confirmarEliminar()"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '{{ config('app.url') }}/api';
        let peliculasOriginal = [];
        let generos = [];
        let peliculaEnEdicion = null;
        let peliculaAEliminar = null;

        // Verificar autenticación y rol admin
        function verificarAutenticacion() {
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user_data');

            if (!token || !userData) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            try {
                const user = JSON.parse(userData);
                if (user.role !== 'admin') {
                    alert('No tienes permiso para acceder a esta sección');
                    window.location.href = '{{ route('dashboard') }}';
                    return;
                }
                document.getElementById('userName').textContent = user.name;
            } catch (error) {
                console.error('Error validando usuario:', error);
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
                    mostrarPeliculas(peliculasOriginal);
                } else {
                    mostrarError('Error al cargar películas: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarError('Error de conexión al cargar películas');
            }
        }

        // Cargar géneros
        async function cargarGeneros() {
            try {
                const response = await fetch(`${API_URL}/generos`);
                const data = await response.json();

                if (response.ok) {
                    generos = data.data || data;
                    const select = document.getElementById('genero_id');
                    select.innerHTML = '<option value="">Seleccionar género...</option>';
                    generos.forEach(gen => {
                        const option = document.createElement('option');
                        option.value = gen.id;
                        option.textContent = gen.nombre;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error cargando géneros:', error);
            }
        }

        // Mostrar películas en tabla
        function mostrarPeliculas(peliculas) {
            const tbody = document.getElementById('tablaBody');
            const noResultados = document.getElementById('noResultados');
            const loader = document.getElementById('loader');
            const contenido = document.getElementById('contenidoPeliculas');

            if (peliculas.length === 0) {
                tbody.innerHTML = '';
                noResultados.classList.remove('hidden');
                contenido.classList.remove('hidden');
            } else {
                tbody.innerHTML = peliculas.map(pelicula => `
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-800 font-medium">${pelicula.titulo}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                ${pelicula.generos && pelicula.generos.length > 0 
                                    ? pelicula.generos[0].nombre 
                                    : 'Sin género'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">${pelicula.duracion} min</td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Activa</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button 
                                    onclick="abrirFormularioEditar(${pelicula.id})"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition"
                                >
                                    Editar
                                </button>
                                <button 
                                    onclick="abrirModalEliminar(${pelicula.id}, '${pelicula.titulo}')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
                noResultados.classList.add('hidden');
                contenido.classList.remove('hidden');
            }

            loader.classList.add('hidden');
        }

        // Filtrar películas
        function filtrarPeliculas() {
            const busqueda = document.getElementById('buscador').value.toLowerCase();
            const filtradas = peliculasOriginal.filter(p => 
                p.titulo.toLowerCase().includes(busqueda)
            );
            mostrarPeliculas(filtradas);
        }

        // Abrir formulario crear
        function abrirFormularioCrear() {
            peliculaEnEdicion = null;
            document.getElementById('modalTitulo').textContent = 'Nueva Película';
            document.getElementById('formularioPelicula').reset();
            document.getElementById('previewImagen').classList.add('hidden');
            document.getElementById('estadoFormulario').textContent = '';
            document.getElementById('modalFormulario').classList.remove('hidden');
        }

        // Abrir formulario editar
        async function abrirFormularioEditar(id) {
            try {
                const response = await fetch(`${API_URL}/peliculas/${id}`);
                const data = await response.json();

                if (response.ok) {
                    const pelicula = data.data || data;
                    peliculaEnEdicion = pelicula;

                    document.getElementById('modalTitulo').textContent = 'Editar Película';
                    document.getElementById('titulo').value = pelicula.titulo;
                    document.getElementById('sinopsis').value = pelicula.sinopsis;
                    document.getElementById('duracion').value = pelicula.duracion;
                    document.getElementById('genero_id').value = pelicula.generos && pelicula.generos.length > 0 ? pelicula.generos[0].id : '';
                    document.getElementById('url_imagen').value = pelicula.url_imagen || '';

                    if (pelicula.url_imagen) {
                        document.getElementById('imagenPreview').src = pelicula.url_imagen;
                        document.getElementById('previewImagen').classList.remove('hidden');
                    }

                    document.getElementById('estadoFormulario').textContent = `ID: ${pelicula.id} | Creada: ${new Date(pelicula.created_at).toLocaleDateString()}`;
                    document.getElementById('modalFormulario').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al cargar película');
            }
        }

        // Cerrar formulario
        function cerrarFormulario() {
            document.getElementById('modalFormulario').classList.add('hidden');
            peliculaEnEdicion = null;
        }

        // Guardar película
        async function guardarPelicula(event) {
            event.preventDefault();

            const datos = {
                titulo: document.getElementById('titulo').value,
                sinopsis: document.getElementById('sinopsis').value,
                duracion: document.getElementById('duracion').value,
                url_imagen: document.getElementById('url_imagen').value
            };

            const generoId = document.getElementById('genero_id').value;
            if (!generoId) {
                alert('Selecciona un género');
                return;
            }

            const metodo = peliculaEnEdicion ? 'PUT' : 'POST';
            const url = peliculaEnEdicion 
                ? `${API_URL}/peliculas/${peliculaEnEdicion.id}`
                : `${API_URL}/peliculas`;

            try {
                const response = await fetch(url, {
                    method: metodo,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    },
                    body: JSON.stringify(datos)
                });

                const data = await response.json();

                if (response.ok) {
                    alert(peliculaEnEdicion ? 'Película actualizada' : 'Película creada');
                    cerrarFormulario();
                    cargarPeliculas();
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        }

        // Abrir modal eliminar
        function abrirModalEliminar(id, titulo) {
            peliculaAEliminar = id;
            document.getElementById('textoConfirmacion').textContent = `¿Estás seguro de que deseas eliminar la película "${titulo}"? Esta acción no se puede deshacer.`;
            document.getElementById('modalEliminar').classList.remove('hidden');
        }

        // Cerrar modal eliminar
        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').classList.add('hidden');
            peliculaAEliminar = null;
        }

        // Confirmar eliminar
        async function confirmarEliminar() {
            try {
                const response = await fetch(`${API_URL}/peliculas/${peliculaAEliminar}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                    }
                });

                if (response.ok) {
                    alert('Película eliminada');
                    cerrarModalEliminar();
                    cargarPeliculas();
                } else {
                    alert('Error al eliminar película');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
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

        // Subir imagen a Firebase
        async function subirImagen() {
            const archivoInput = document.getElementById('imagenFile');
            const btnSubir = document.getElementById('btnSubirImagen');
            const estadoUpload = document.getElementById('estadoUpload');
            const token = localStorage.getItem('auth_token');
            const peliculaId = document.getElementById('peliculaId').value;

            if (!archivoInput.files[0]) {
                alert('Por favor selecciona una imagen');
                return;
            }

            if (!peliculaId) {
                alert('Debe guardar la película primero');
                return;
            }

            btnSubir.disabled = true;
            btnSubir.textContent = '⏳ Subiendo...';
            estadoUpload.innerHTML = '⏳ Subiendo imagen a Firebase...';

            try {
                const formData = new FormData();
                formData.append('imagen', archivoInput.files[0]);

                const response = await fetch(`/api/peliculas/${peliculaId}/upload-imagen`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    estadoUpload.innerHTML = `✅ ${data.message}`;
                    document.getElementById('url_imagen').value = data.url;
                    document.getElementById('imagenPreview').src = data.url;
                    document.getElementById('previewImagen').classList.remove('hidden');
                    archivoInput.value = ''; // Limpiar input
                    alert('Imagen subida correctamente a Firebase Storage');
                } else {
                    estadoUpload.innerHTML = `❌ Error: ${data.message}`;
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                estadoUpload.innerHTML = `❌ Error de conexión`;
                alert('Error de conexión');
            } finally {
                btnSubir.disabled = false;
                btnSubir.textContent = '📤 Subir Imagen a Firebase Storage';
            }
        }

        // Actualizar preview imagen
        document.addEventListener('DOMContentLoaded', function() {
            verificarAutenticacion();
            cargarPeliculas();
            cargarGeneros();

            // Habilitar botón de upload cuando se selecciona archivo
            document.getElementById('imagenFile').addEventListener('change', function() {
                document.getElementById('btnSubirImagen').disabled = false;
            });

            document.getElementById('url_imagen').addEventListener('change', function(e) {
                if (e.target.value) {
                    document.getElementById('imagenPreview').src = e.target.value;
                    document.getElementById('previewImagen').classList.remove('hidden');
                } else {
                    document.getElementById('previewImagen').classList.add('hidden');
                }
            });
        });

        // === FUNCIONES TMDB ===

        // Buscar películas en TMDb
        async function buscarEnTMDb() {
            const query = document.getElementById('tmdbBuscador').value.trim();
            if (!query) {
                alert('Ingresa un término de búsqueda');
                return;
            }

            document.getElementById('loaderTMDb').classList.remove('hidden');
            document.getElementById('resultadosTMDb').classList.add('hidden');

            try {
                const response = await fetch(`${API_URL}/tmdb/search?query=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (response.ok) {
                    mostrarResultadosTMDb(data.data || data);
                } else {
                    alert('Error en búsqueda: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión con TMDb');
            } finally {
                document.getElementById('loaderTMDb').classList.add('hidden');
            }
        }

        // Cargar películas populares de TMDb
        async function cargarPopularesTMDb() {
            document.getElementById('loaderTMDb').classList.remove('hidden');
            document.getElementById('resultadosTMDb').classList.add('hidden');

            try {
                const response = await fetch(`${API_URL}/tmdb/popular`);
                const data = await response.json();

                if (response.ok) {
                    mostrarResultadosTMDb(data.data || data);
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión con TMDb');
            } finally {
                document.getElementById('loaderTMDb').classList.add('hidden');
            }
        }

        // Mostrar resultados de TMDb
        function mostrarResultadosTMDb(resultados) {
            const contenedor = document.getElementById('resultadosTMDb');
            
            if (resultados.length === 0) {
                contenedor.innerHTML = '<p class="col-span-full text-center text-gray-600">No se encontraron películas</p>';
                contenedor.classList.remove('hidden');
                return;
            }

            contenedor.innerHTML = resultados.map(pelicula => `
                <div class="bg-white border-2 border-yellow-300 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <div class="w-full h-32 bg-gray-200 overflow-hidden">
                        ${pelicula.poster ? `<img src="${pelicula.poster}" alt="${pelicula.titulo}" class="w-full h-full object-cover">` : '🎬'}
                    </div>
                    <div class="p-3">
                        <h4 class="font-semibold text-sm text-gray-800 line-clamp-2">${pelicula.titulo}</h4>
                        ${pelicula.calificacion ? `<p class="text-xs text-yellow-600 mb-2">⭐ ${pelicula.calificacion}</p>` : ''}
                        <button 
                            onclick="importarDesdeTMDb(${JSON.stringify(pelicula).replace(/"/g, '&quot;')})"
                            class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-2 py-2 rounded text-xs font-semibold transition"
                        >
                            ➕ Importar
                        </button>
                    </div>
                </div>
            `).join('');

            contenedor.classList.remove('hidden');
        }

        // Importar película desde TMDb
        function importarDesdeTMDb(pelicula) {
            document.getElementById('modalTitulo').textContent = 'Nueva Película (desde TMDb)';
            document.getElementById('titulo').value = pelicula.titulo || '';
            document.getElementById('sinopsis').value = pelicula.sinopsis || '';
            document.getElementById('duracion').value = pelicula.duracion || 0;
            document.getElementById('url_imagen').value = pelicula.poster || '';

            if (pelicula.poster) {
                document.getElementById('imagenPreview').src = pelicula.poster;
                document.getElementById('previewImagen').classList.remove('hidden');
            }

            document.getElementById('estadoFormulario').textContent = `Importada de TMDb | Calificación: ${pelicula.calificacion || 'N/A'}`;
            peliculaEnEdicion = null;
            document.getElementById('modalFormulario').classList.remove('hidden');
            
            // Scroll al formulario
            document.getElementById('modalFormulario').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
</body>
</html>
