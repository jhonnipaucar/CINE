<?php

namespace Tests\Feature;

use App\Models\Pelicula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeliculaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all movies
     */
    public function test_index_returns_all_peliculas()
    {
        Pelicula::factory()->count(3)->create();

        $response = $this->getJson('/api/peliculas');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Test creating a movie
     */
    public function test_store_creates_pelicula()
    {
        $peliculaData = [
            'titulo' => 'Inception',
            'sinopsis' => 'A mind-bending thriller',
            'duracion' => 148,
            'poster_url' => 'https://example.com/poster.jpg'
        ];

        $response = $this->postJson('/api/peliculas', $peliculaData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['titulo' => 'Inception']);

        $this->assertDatabaseHas('peliculas', ['titulo' => 'Inception']);
    }

    /**
     * Test store validation fails without titulo
     */
    public function test_store_validation_fails_without_titulo()
    {
        $peliculaData = [
            'sinopsis' => 'A movie',
            'duracion' => 120,
            'poster_url' => 'https://example.com/poster.jpg'
        ];

        $response = $this->postJson('/api/peliculas', $peliculaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('titulo');
    }

    /**
     * Test store validation fails without sinopsis
     */
    public function test_store_validation_fails_without_sinopsis()
    {
        $peliculaData = [
            'titulo' => 'Movie Title',
            'duracion' => 120,
            'poster_url' => 'https://example.com/poster.jpg'
        ];

        $response = $this->postJson('/api/peliculas', $peliculaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('sinopsis');
    }

    /**
     * Test store validation fails without duracion
     */
    public function test_store_validation_fails_without_duracion()
    {
        $peliculaData = [
            'titulo' => 'Movie Title',
            'sinopsis' => 'A movie',
            'poster_url' => 'https://example.com/poster.jpg'
        ];

        $response = $this->postJson('/api/peliculas', $peliculaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('duracion');
    }

    /**
     * Test store validation fails without poster_url
     */
    public function test_store_validation_fails_without_poster_url()
    {
        $peliculaData = [
            'titulo' => 'Movie Title',
            'sinopsis' => 'A movie',
            'duracion' => 120
        ];

        $response = $this->postJson('/api/peliculas', $peliculaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('poster_url');
    }

    /**
     * Test getting a specific movie
     */
    public function test_show_returns_pelicula()
    {
        $pelicula = Pelicula::factory()->create(['titulo' => 'The Matrix']);

        $response = $this->getJson("/api/peliculas/{$pelicula->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['titulo' => 'The Matrix']);
    }

    /**
     * Test show returns 404 for non-existent movie
     */
    public function test_show_returns_404_for_non_existent_pelicula()
    {
        $response = $this->getJson('/api/peliculas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Pelicula no encontrada']);
    }

    /**
     * Test updating a movie
     */
    public function test_update_modifies_pelicula()
    {
        $pelicula = Pelicula::factory()->create(['titulo' => 'Avatar']);

        $updateData = ['titulo' => 'Avatar 2'];

        $response = $this->putJson("/api/peliculas/{$pelicula->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['titulo' => 'Avatar 2']);

        $this->assertDatabaseHas('peliculas', ['id' => $pelicula->id, 'titulo' => 'Avatar 2']);
    }

    /**
     * Test update returns 404 for non-existent movie
     */
    public function test_update_returns_404_for_non_existent_pelicula()
    {
        $response = $this->putJson('/api/peliculas/999', ['titulo' => 'Unknown Movie']);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Pelicula no encontrada']);
    }

    /**
     * Test deleting a movie
     */
    public function test_destroy_deletes_pelicula()
    {
        $pelicula = Pelicula::factory()->create(['titulo' => 'Interstellar']);

        $response = $this->deleteJson("/api/peliculas/{$pelicula->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Pelicula eliminada']);

        $this->assertDatabaseMissing('peliculas', ['id' => $pelicula->id]);
    }

    /**
     * Test destroy returns 404 for non-existent movie
     */
    public function test_destroy_returns_404_for_non_existent_pelicula()
    {
        $response = $this->deleteJson('/api/peliculas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Pelicula no encontrada']);
    }
}
