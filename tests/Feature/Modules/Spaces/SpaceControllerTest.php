<?php

namespace Tests\Feature\Modules\Spaces;

use App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test; // <-- Importa el atributo

use Tests\TestCase;

class SpaceControllerTest extends TestCase
{
    // Este trait mágico resetea la base de datos antes de cada prueba.
    // ¡Esencial para que las pruebas no interfieran entre sí!
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_space(): void
    {
        // Arrange
        $spaceData = [
            'name' => 'Nuevo Espacio de Prueba',
            'type' => 'auditorio',
            'capacity' => 75,
        ];

        // Act
        $response = $this->postJson('/api/spaces', $spaceData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonFragment($spaceData); // Verifica que la respuesta JSON contiene los datos

        $this->assertDatabaseHas('spaces', $spaceData); // Verifica que los datos están en la BD
    }

    #[Test]
    public function it_can_list_all_spaces(): void
    {
        // Arrange
        // Creamos 3 espacios directamente usando el modelo de Eloquent
        SpaceEloquentModel::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/spaces');

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(3); // Verifica que la respuesta es un array con 3 elementos
    }

    #[Test]
    public function it_can_update_a_space(): void
    {
        // Arrange
        $space = SpaceEloquentModel::factory()->create();
        $updateData = [
            'name' => 'Espacio Actualizado',
            'type' => 'salón',
            'capacity' => 150,
        ];

        // Act
        $response = $this->putJson("/api/spaces/{$space->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('spaces', array_merge(['id' => $space->id], $updateData));
    }

    #[Test]
    public function it_can_delete_a_space(): void
    {
        // Arrange
        $space = SpaceEloquentModel::factory()->create();

        // Act
        $response = $this->deleteJson("/api/spaces/{$space->id}");

        // Assert
        $response->assertStatus(204); // 204 No Content

        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }
}
