<?php

namespace Tests\Feature;

use App\Models\Sala;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_salas()
    {
        Sala::factory()->count(3)->create();

        $response = $this->getJson('/api/salas');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_create_sala()
    {
        $data = [
            'nombre' => 'Sala 1',
            'capacidad' => 60
        ];

        $response = $this->postJson('/api/salas', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['nombre' => 'Sala 1']);
        $this->assertDatabaseHas('salas', ['nombre' => 'Sala 1']);
    }

    public function test_get_single_sala()
    {
        $sala = Sala::factory()->create();

        $response = $this->getJson("/api/salas/{$sala->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => $sala->nombre]);
    }

    public function test_update_sala()
    {
        $sala = Sala::factory()->create();

        $data = [
            'nombre' => 'Sala Actualizada',
            'capacidad' => 100
        ];

        $response = $this->putJson("/api/salas/{$sala->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'Sala Actualizada']);
    }

    public function test_delete_sala()
    {
        $sala = Sala::factory()->create();

        $response = $this->deleteJson("/api/salas/{$sala->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('salas', ['id' => $sala->id]);
    }
}
