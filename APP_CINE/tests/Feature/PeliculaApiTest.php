<?php

namespace Tests\Feature;

use App\Models\Pelicula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeliculaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_peliculas()
    {
        Pelicula::factory()->count(3)->create();

        $response = $this->getJson('/api/peliculas');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_create_pelicula()
    {
        $data = [
            'titulo' => 'Película Test',
            'sinopsis' => 'Una película de prueba',
            'duracion' => 120,
            'poster_url' => 'https://example.com/poster.jpg'
        ];

        $response = $this->postJson('/api/peliculas', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['titulo' => 'Película Test']);
        $this->assertDatabaseHas('peliculas', ['titulo' => 'Película Test']);
    }

    public function test_get_single_pelicula()
    {
        $pelicula = Pelicula::factory()->create();

        $response = $this->getJson("/api/peliculas/{$pelicula->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['titulo' => $pelicula->titulo]);
    }

    public function test_update_pelicula()
    {
        $pelicula = Pelicula::factory()->create();

        $data = [
            'titulo' => 'Título Actualizado',
            'sinopsis' => 'Sinopsis actualizada',
            'duracion' => 150,
            'poster_url' => 'https://example.com/new-poster.jpg'
        ];

        $response = $this->putJson("/api/peliculas/{$pelicula->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['titulo' => 'Título Actualizado']);
        $this->assertDatabaseHas('peliculas', ['titulo' => 'Título Actualizado']);
    }

    public function test_delete_pelicula()
    {
        $pelicula = Pelicula::factory()->create();

        $response = $this->deleteJson("/api/peliculas/{$pelicula->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('peliculas', ['id' => $pelicula->id]);
    }

    public function test_get_nonexistent_pelicula()
    {
        $response = $this->getJson('/api/peliculas/999');

        $response->assertStatus(404);
    }
}
