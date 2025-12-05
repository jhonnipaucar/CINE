<?php

namespace Tests\Feature;

use App\Models\Reserva;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all reservations
     */
    public function test_index_returns_all_reservas()
    {
        // Crear directamente sin factory para evitar problemas con fechas
        $user = User::factory()->create();
        Reserva::create([
            'funcion_id' => Funcion::create([
                'pelicula_id' => Pelicula::factory()->create()->id,
                'sala_id' => Sala::factory()->create()->id,
                'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
                'precio' => 12.00
            ])->id,
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ]);

        $response = $this->getJson('/api/reservas');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /**
     * Test creating a reservation
     */
    public function test_store_creates_reserva()
    {
        $funcion = Funcion::create([
            'pelicula_id' => Pelicula::factory()->create()->id,
            'sala_id' => Sala::factory()->create()->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);
        $user = User::factory()->create();

        $reservaData = [
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada',
            'comentarios' => 'Window seats please'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'funcion_id' => $funcion->id,
                     'user_id' => $user->id,
                     'asientos' => 2,
                     'estado' => 'confirmada'
                 ]);

        $this->assertDatabaseHas('reservas', ['funcion_id' => $funcion->id, 'user_id' => $user->id]);
    }

    /**
     * Test store validation fails without funcion_id
     */
    public function test_store_validation_fails_without_funcion_id()
    {
        $user = User::factory()->create();

        $reservaData = [
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('funcion_id');
    }

    /**
     * Test store validation fails with non-existent funcion_id
     */
    public function test_store_validation_fails_with_non_existent_funcion_id()
    {
        $user = User::factory()->create();

        $reservaData = [
            'funcion_id' => 999,
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('funcion_id');
    }

    /**
     * Test store validation fails without user_id
     */
    public function test_store_validation_fails_without_user_id()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $reservaData = [
            'funcion_id' => $funcion->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('user_id');
    }

    /**
     * Test store validation fails with non-existent user_id
     */
    public function test_store_validation_fails_with_non_existent_user_id()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $reservaData = [
            'funcion_id' => $funcion->id,
            'user_id' => 999,
            'asientos' => 2,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('user_id');
    }

    /**
     * Test store validation fails without asientos
     */
    public function test_store_validation_fails_without_asientos()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $user = User::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $reservaData = [
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('asientos');
    }

    /**
     * Test store validation fails with asientos less than 1
     */
    public function test_store_validation_fails_with_asientos_less_than_one()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $user = User::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $reservaData = [
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'asientos' => 0,
            'estado' => 'confirmada'
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('asientos');
    }

    /**
     * Test store validation fails without estado
     */
    public function test_store_validation_fails_without_estado()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $user = User::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);

        $reservaData = [
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'asientos' => 2
        ];

        $response = $this->postJson('/api/reservas', $reservaData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('estado');
    }

    /**
     * Test getting a specific reservation
     */
    public function test_show_returns_reserva()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $user = User::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);
        
        $reserva = Reserva::create([
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ]);

        $response = $this->getJson("/api/reservas/{$reserva->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $reserva->id]);
    }

    /**
     * Test show returns 404 for non-existent reservation
     */
    public function test_show_returns_404_for_non_existent_reserva()
    {
        $response = $this->getJson('/api/reservas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Reserva no encontrada']);
    }

    /**
     * Test updating a reservation
     */
    public function test_update_modifies_reserva()
    {
        $funcion = Funcion::create([
            'pelicula_id' => Pelicula::factory()->create()->id,
            'sala_id' => Sala::factory()->create()->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);
        
        $reserva = Reserva::create([
            'funcion_id' => $funcion->id,
            'user_id' => User::factory()->create()->id,
            'asientos' => 2,
            'estado' => 'pendiente'
        ]);

        $updateData = ['estado' => 'confirmada'];

        $response = $this->putJson("/api/reservas/{$reserva->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['estado' => 'confirmada']);

        $this->assertDatabaseHas('reservas', ['id' => $reserva->id, 'estado' => 'confirmada']);
    }

    /**
     * Test update returns 404 for non-existent reservation
     */
    public function test_update_returns_404_for_non_existent_reserva()
    {
        $response = $this->putJson('/api/reservas/999', ['estado' => 'cancelada']);

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Reserva no encontrada']);
    }

    /**
     * Test deleting a reservation
     */
    public function test_destroy_deletes_reserva()
    {
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $user = User::factory()->create();
        
        $funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => now()->addDays(5)->format('Y-m-d H:i:s'),
            'precio' => 12.00
        ]);
        
        $reserva = Reserva::create([
            'funcion_id' => $funcion->id,
            'user_id' => $user->id,
            'asientos' => 2,
            'estado' => 'confirmada'
        ]);

        $response = $this->deleteJson("/api/reservas/{$reserva->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Reserva eliminada']);

        $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
    }

    /**
     * Test destroy returns 404 for non-existent reservation
     */
    public function test_destroy_returns_404_for_non_existent_reserva()
    {
        $response = $this->deleteJson('/api/reservas/999');

        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Reserva no encontrada']);
    }
}
