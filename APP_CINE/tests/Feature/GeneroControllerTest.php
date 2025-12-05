<?php

namespace Tests\Feature;

use App\Models\Genero;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneroControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all genres
     */
    public function test_index_returns_all_generos()
    {
        Genero::factory()->count(3)->create();

        $response = $this->getJson('/api/generos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Test creating a genre
     */
    public function test_store_creates_genero()
    {
        $generoData = ['nombre' => 'Acción'];

        $response = $this->postJson('/api/generos', $generoData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nombre' => 'Acción']);

        $this->assertDatabaseHas('generos', ['nombre' => 'Acción']);
    }

    /**
     * Test store validation fails without required fields
     */
    public function test_store_validation_fails_without_nombre()
    {
        $response = $this->postJson('/api/generos', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('nombre');
    }

    /**
     * Test store validation fails with string longer than 255 characters
     */
    public function test_store_validation_fails_with_nombre_too_long()
    {
        $generoData = ['nombre' => str_repeat('a', 256)];

        $response = $this->postJson('/api/generos', $generoData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('nombre');
    }

    /**
     * Test getting a specific genre
     */
    public function test_show_returns_genero()
    {
        $genero = Genero::factory()->create(['nombre' => 'Drama']);

        $response = $this->getJson("/api/generos/{$genero->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Drama']);
    }

    /**
     * Test show returns 404 for non-existent genre
     */
    public function test_show_returns_404_for_non_existent_genero()
    {
        $response = $this->getJson('/api/generos/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Género no encontrado']);
    }

    /**
     * Test updating a genre
     */
    public function test_update_modifies_genero()
    {
        $genero = Genero::factory()->create(['nombre' => 'Comedia']);

        $updateData = ['nombre' => 'Comedia Romántica'];

        $response = $this->putJson("/api/generos/{$genero->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Comedia Romántica']);

        $this->assertDatabaseHas('generos', ['id' => $genero->id, 'nombre' => 'Comedia Romántica']);
    }

    /**
     * Test update returns 404 for non-existent genre
     */
    public function test_update_returns_404_for_non_existent_genero()
    {
        $response = $this->putJson('/api/generos/999', ['nombre' => 'Terror']);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Género no encontrado']);
    }

    /**
     * Test deleting a genre
     */
    public function test_destroy_deletes_genero()
    {
        $genero = Genero::factory()->create(['nombre' => 'Suspense']);

        $response = $this->deleteJson("/api/generos/{$genero->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Género eliminado']);

        $this->assertDatabaseMissing('generos', ['id' => $genero->id]);
    }

    /**
     * Test destroy returns 404 for non-existent genre
     */
    public function test_destroy_returns_404_for_non_existent_genero()
    {
        $response = $this->deleteJson('/api/generos/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Género no encontrado']);
    }
}
