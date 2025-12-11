<?php

namespace Tests\Feature;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Funcion;
use App\Models\Pelicula;
use App\Models\Sala;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $funcion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
        
        // Create Funcion without using factory to avoid datetime issues with SQL Server
        $pelicula = Pelicula::factory()->create();
        $sala = Sala::factory()->create();
        $this->funcion = Funcion::create([
            'pelicula_id' => $pelicula->id,
            'sala_id' => $sala->id,
            'fecha' => '2025-12-25 20:00:00',
            'precio' => 12.50
        ]);
    }

    public function test_get_all_reservas()
    {
        // Create 3 reservas manually without factory to avoid datetime issues
        for ($i = 0; $i < 3; $i++) {
            Reserva::create([
                'user_id' => $this->user->id,
                'funcion_id' => $this->funcion->id,
                'asientos' => rand(1, 5),
                'estado' => 'pendiente',
                'comentarios' => 'Test'
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/reservas');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_create_reserva()
    {
        $data = [
            'user_id' => $this->user->id,
            'funcion_id' => $this->funcion->id,
            'asientos' => 2,
            'estado' => 'pendiente',
            'comentarios' => 'Reserva de prueba'
        ];

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/reservas', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reservas', ['user_id' => $this->user->id]);
    }

    public function test_get_single_reserva()
    {
        $reserva = Reserva::create([
            'user_id' => $this->user->id,
            'funcion_id' => $this->funcion->id,
            'asientos' => 2,
            'estado' => 'pendiente'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson("/api/reservas/{$reserva->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['estado' => $reserva->estado]);
    }

    public function test_update_reserva()
    {
        $reserva = Reserva::create([
            'user_id' => $this->user->id,
            'funcion_id' => $this->funcion->id,
            'asientos' => 2,
            'estado' => 'pendiente'
        ]);

        $data = [
            'user_id' => $reserva->user_id,
            'funcion_id' => $reserva->funcion_id,
            'asientos' => 3,
            'estado' => 'confirmada',
            'comentarios' => 'Actualizada'
        ];

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/reservas/{$reserva->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['estado' => 'confirmada']);
    }

    public function test_delete_reserva()
    {
        $reserva = Reserva::create([
            'user_id' => $this->user->id,
            'funcion_id' => $this->funcion->id,
            'asientos' => 2,
            'estado' => 'pendiente'
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/reservas/{$reserva->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
    }

    public function test_get_nonexistent_reserva()
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/reservas/999');

        $response->assertStatus(404);
    }
}
