<?php

namespace Tests\Feature;

use App\Models\Sala;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all rooms
     */
    public function test_index_returns_all_salas()
    {
        Sala::factory()->count(3)->create();

        $response = $this->getJson('/api/salas');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Test creating a room
     */
    public function test_store_creates_sala()
    {
        $salaData = [
            'nombre' => 'Sala 1',
            'capacidad' => 100
        ];

        $response = $this->postJson('/api/salas', $salaData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['nombre' => 'Sala 1', 'capacidad' => 100]);

        $this->assertDatabaseHas('salas', ['nombre' => 'Sala 1']);
    }

    /**
     * Test store validation fails without nombre
     */
    public function test_store_validation_fails_without_nombre()
    {
        $salaData = ['capacidad' => 100];

        $response = $this->postJson('/api/salas', $salaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('nombre');
    }

    /**
     * Test store validation fails without capacidad
     */
    public function test_store_validation_fails_without_capacidad()
    {
        $salaData = ['nombre' => 'Sala 1'];

        $response = $this->postJson('/api/salas', $salaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('capacidad');
    }

    /**
     * Test store validation fails with non-integer capacidad
     */
    public function test_store_validation_fails_with_non_integer_capacidad()
    {
        $salaData = [
            'nombre' => 'Sala 1',
            'capacidad' => 'one hundred'
        ];

        $response = $this->postJson('/api/salas', $salaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('capacidad');
    }

    /**
     * Test getting a specific room
     */
    public function test_show_returns_sala()
    {
        $sala = Sala::factory()->create(['nombre' => 'Sala VIP']);

        $response = $this->getJson("/api/salas/{$sala->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Sala VIP']);
    }

    /**
     * Test show returns 404 for non-existent room
     */
    public function test_show_returns_404_for_non_existent_sala()
    {
        $response = $this->getJson('/api/salas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Sala no encontrada']);
    }

    /**
     * Test updating a room
     */
    public function test_update_modifies_sala()
    {
        $sala = Sala::factory()->create(['nombre' => 'Sala 2', 'capacidad' => 80]);

        $updateData = ['capacidad' => 120];

        $response = $this->putJson("/api/salas/{$sala->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['capacidad' => 120]);

        $this->assertDatabaseHas('salas', ['id' => $sala->id, 'capacidad' => 120]);
    }

    /**
     * Test update returns 404 for non-existent room
     */
    public function test_update_returns_404_for_non_existent_sala()
    {
        $response = $this->putJson('/api/salas/999', ['nombre' => 'Unknown Room']);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Sala no encontrada']);
    }

    /**
     * Test deleting a room
     */
    public function test_destroy_deletes_sala()
    {
        $sala = Sala::factory()->create(['nombre' => 'Sala Temporal']);

        $response = $this->deleteJson("/api/salas/{$sala->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Sala eliminada']);

        $this->assertDatabaseMissing('salas', ['id' => $sala->id]);
    }

    /**
     * Test destroy returns 404 for non-existent room
     */
    public function test_destroy_returns_404_for_non_existent_sala()
    {
        $response = $this->deleteJson('/api/salas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Sala no encontrada']);
    }
}
