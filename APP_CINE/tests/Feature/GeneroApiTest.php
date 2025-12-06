<?php

namespace Tests\Feature;

use App\Models\Genero;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneroApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_generos()
    {
        Genero::factory()->count(3)->create();

        $response = $this->getJson('/api/generos');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_create_genero()
    {
        $data = ['nombre' => 'Acción'];

        $response = $this->postJson('/api/generos', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['nombre' => 'Acción']);
        $this->assertDatabaseHas('generos', ['nombre' => 'Acción']);
    }

    public function test_get_single_genero()
    {
        $genero = Genero::factory()->create();

        $response = $this->getJson("/api/generos/{$genero->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => $genero->nombre]);
    }

    public function test_update_genero()
    {
        $genero = Genero::factory()->create(['nombre' => 'Terror']);

        $data = ['nombre' => 'Horror'];

        $response = $this->putJson("/api/generos/{$genero->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'Horror']);
    }

    public function test_delete_genero()
    {
        $genero = Genero::factory()->create();

        $response = $this->deleteJson("/api/generos/{$genero->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('generos', ['id' => $genero->id]);
    }
}
