<?php
// Test de TMDB API
$apiKey = '18db2bf82201ad148c3d7e4e39033511';
$baseUrl = 'https://api.themoviedb.org/3';

$url = "$baseUrl/movie/popular?api_key=$apiKey&language=es-ES&page=1";

echo "Testing TMDB API:\n";
echo "URL: $url\n\n";

$response = @file_get_contents($url);

if ($response === false) {
    echo "ERROR: No se pudo conectar a TMDB API\n";
    echo "Verifica que:\n";
    echo "1. Tienes conexión a internet\n";
    echo "2. La API Key es válida\n";
    echo "3. allow_url_fopen está habilitado en php.ini\n";
} else {
    $data = json_decode($response, true);
    if (isset($data['results'])) {
        echo "SUCCESS: Se cargaron " . count($data['results']) . " películas\n";
        echo "Primera película: " . $data['results'][0]['title'] . "\n";
    } else {
        echo "ERROR en respuesta:\n";
        echo $response . "\n";
    }
}
