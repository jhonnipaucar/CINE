<?php

namespace Tests\Feature;

use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FuncionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_funciones()
    {
        Funcion::factory()->count(3)->create();

        $response = $this->getJson('/api/funciones');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_get_single_funcion()
    {
        $funcion = Funcion::factory()->create();

        $response = $this->getJson("/api/funciones/{$funcion->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['precio' => $funcion->precio]);
    }

    public function test_delete_funcion()
    {
        $funcion = Funcion::factory()->create();

        $response = $this->deleteJson("/api/funciones/{$funcion->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('funciones', ['id' => $funcion->id]);
    }
}
