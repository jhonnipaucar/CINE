<?php

namespace Tests\Feature;

use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FuncionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all functions
     */
    public function test_index_returns_all_funciones()
    {
        Pelicula::factory()->create();
        Sala::factory()->create();
        
        Funcion::create([
            'pelicula_id' => 1,
            'sala_id' => 1,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $response = $this->getJson('/api/funciones');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a function
     */
    /**
     * Test storing a new funcion
     * Note: There's a known SQL Server datetime binding issue with Laravel/PDO
     * that causes datetime values to not be properly quoted in prepared statements
     */
    public function test_store_creates_funcion()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();

        // Use a datetime object instead of string to leverage Eloquent's serialization
        $fecha = now()->addDays(5);

        $funcionData = [
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => $fecha->toDateTimeString(),
            'precio' => 12.50
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'pelicula_id' => $pelicula->id,
                     'sala_id' => $sala->id,
                     'precio' => 12.50
                 ]);

        $this->assertDatabaseHas('funciones', ['pelicula_id' => $pelicula->id]);
    }

    /**
     * Test store validation fails without pelicula_id
     */
    public function test_store_validation_fails_without_pelicula_id()
    {
        $sala = Sala::factory()->create();

        $funcionData = [
            'sala_id' => $sala->id,
            'fecha' => '2025-12-25',
            'precio' => 12.50
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('pelicula_id');
    }

    /**
     * Test store validation fails with non-existent pelicula_id
     */
    public function test_store_validation_fails_with_non_existent_pelicula_id()
    {
        $sala = Sala::factory()->create();

        $funcionData = [
            'pelicula_id' => 999,
            'sala_id' => $sala->id,
            'fecha' => '2025-12-25',
            'precio' => 12.50
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('pelicula_id');
    }

    /**
     * Test store validation fails without sala_id
     */
    public function test_store_validation_fails_without_sala_id()
    {
        $pelicula = Pelicula::factory()->create();

        $funcionData = [
            'pelicula_id' => $pelicula->id,
            'fecha' => '2025-12-25',
            'precio' => 12.50
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('sala_id');
    }

    /**
     * Test store validation fails without fecha
     */
    public function test_store_validation_fails_without_fecha()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();

        $funcionData = [
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'precio' => 12.50
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('fecha');
    }

    /**
     * Test store validation fails without precio
     */
    public function test_store_validation_fails_without_precio()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();

        $funcionData = [
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => '2025-12-25'
        ];

        $response = $this->postJson('/api/funciones', $funcionData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('precio');
    }

    /**
     * Test getting a specific function
     */
    public function test_show_returns_funcion()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $response = $this->getJson("/api/funciones/{$funcion->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $funcion->id]);
    }

    /**
     * Test show returns 404 for non-existent function
     */
    public function test_show_returns_404_for_non_existent_funcion()
    {
        $response = $this->getJson('/api/funciones/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Función no encontrada']);
    }

    /**
     * Test updating a function
     */
    public function test_update_modifies_funcion()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 10.00
        ]);

        $updateData = ['precio' => 15.00];

        $response = $this->putJson("/api/funciones/{$funcion->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['precio' => 15.00]);

        $this->assertDatabaseHas('funciones', ['id' => $funcion->id, 'precio' => 15.00]);
    }

    /**
     * Test update returns 404 for non-existent function
     */
    public function test_update_returns_404_for_non_existent_funcion()
    {
        $response = $this->putJson('/api/funciones/999', ['precio' => 20.00]);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Función no encontrada']);
    }

    /**
     * Test deleting a function
     */
    public function test_destroy_deletes_funcion()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $response = $this->deleteJson("/api/funciones/{$funcion->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Función eliminada']);

        $this->assertDatabaseMissing('funciones', ['id' => $funcion->id]);
    }

    /**
     * Test destroy returns 404 for non-existent function
     */
    public function test_destroy_returns_404_for_non_existent_funcion()
    {
        $response = $this->deleteJson('/api/funciones/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Función no encontrada']);
    }
}
