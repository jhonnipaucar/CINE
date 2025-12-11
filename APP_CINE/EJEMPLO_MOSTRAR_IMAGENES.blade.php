<!-- Ejemplo: Mostrar películas con imágenes de Firebase en Dashboard -->
<!-- Agregar este código en tu dashboard.blade.php o catalogo.blade.php -->

<div class="peliculas-grid">
    <script>
        // Cargar películas desde API
        const API_URL = 'http://127.0.0.1:8000/api';

        async function cargarPeliculas() {
            try {
                const response = await fetch(`${API_URL}/peliculas`);
                const data = await response.json();

                const container = document.getElementById('peliculas-container');
                container.innerHTML = '';

                if (data.data && data.data.length > 0) {
                    data.data.forEach(pelicula => {
                        const imagenUrl = pelicula.image_url || pelicula.url_imagen || pelicula.poster_url;
                        
                        const html = `
                            <div class="pelicula-card">
                                <div class="pelicula-imagen">
                                    <img src="${imagenUrl}" 
                                         alt="${pelicula.titulo}"
                                         onerror="this.src='https://via.placeholder.com/300x450?text=Sin+Imagen'"
                                         loading="lazy">
                                </div>
                                <div class="pelicula-info">
                                    <h3>${pelicula.titulo}</h3>
                                    <p>${pelicula.sinopsis ? pelicula.sinopsis.substring(0, 100) + '...' : ''}</p>
                                    <div class="pelicula-acciones">
                                        <button onclick="verDetalles(${pelicula.id})">Ver Detalles</button>
                                        <button onclick="reservar(${pelicula.id})">Reservar</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<p>No hay películas disponibles</p>';
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('peliculas-container').innerHTML = 
                    '<p>Error al cargar películas</p>';
            }
        }

        // Cargar películas al iniciar página
        document.addEventListener('DOMContentLoaded', cargarPeliculas);

        function verDetalles(id) {
            // Implementar lógica para ver detalles
            console.log('Ver película:', id);
        }

        function reservar(id) {
            // Implementar lógica de reserva
            console.log('Reservar película:', id);
        }
    </script>
</div>

<style>
    .peliculas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .pelicula-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }

    .pelicula-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .pelicula-imagen {
        width: 100%;
        height: 400px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .pelicula-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pelicula-info {
        padding: 15px;
    }

    .pelicula-info h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        color: #333;
    }

    .pelicula-info p {
        margin: 0 0 15px 0;
        font-size: 14px;
        color: #666;
        line-height: 1.4;
    }

    .pelicula-acciones {
        display: flex;
        gap: 10px;
    }

    .pelicula-acciones button {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        transition: opacity 0.2s;
    }

    .pelicula-acciones button:hover {
        opacity: 0.9;
    }
</style>
