<?php

namespace Tests\Unit\Modules\Spaces;

use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Spaces\Application\UseCases\UpdateSpaceUseCase;
use App\Modules\Spaces\Domain\Space;
use PHPUnit\Framework\Attributes\Test;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateSpaceUseCaseTest extends TestCase
{
    private MockInterface|SpaceRepositoryPort $spaceRepositoryMock;
    private UpdateSpaceUseCase $updateSpaceUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        // Creamos un "doble de prueba" (mock) para el repositorio.
        // Nuestro caso de uso hablará con este mock, no con la BD real.
        $this->spaceRepositoryMock = Mockery::mock(SpaceRepositoryPort::class);
        $this->updateSpaceUseCase = new UpdateSpaceUseCase($this->spaceRepositoryMock);
    }

    #[Test]
    public function it_should_update_an_existing_space(): void
    {
        // Arrange (Preparar)
        $spaceId = 1;
        $updateData = ['name' => 'Salón Renovado', 'type' => 'salón', 'capacity' => 120];

        // Creamos una entidad de dominio que simula lo que hay en la BD
        $initialSpaceEntity = new Space($spaceId, 'Salón Viejo', 'salón', 100);

        // Le decimos al mock qué hacer cuando se le llame
        $this->spaceRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($spaceId)
            ->andReturn($initialSpaceEntity);

        // Esperamos que se llame al método 'save' con una entidad que tenga los datos actualizados
        $this->spaceRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (Space $space) use ($updateData, $spaceId) {
                // Verificamos que los datos del objeto que se va a guardar son correctos
                return $space->id === $spaceId &&
                       $space->name === $updateData['name'] &&
                       $space->capacity === $updateData['capacity'];
            }))
            ->andReturnUsing(function(Space $space) { return $space; }); // Devuelve la misma entidad guardada

        // Act (Actuar)
        $result = $this->updateSpaceUseCase->execute($spaceId, $updateData['name'], $updateData['type'], $updateData['capacity']);

        // Assert (Verificar)
        $this->assertInstanceOf(Space::class, $result);
        $this->assertEquals($updateData['name'], $result->name);
        $this->assertEquals($updateData['capacity'], $result->capacity);
    }

    #[Test]
    public function it_should_throw_exception_if_space_to_update_is_not_found(): void
    {
        // Arrange
        $this->expectException(EntityNotFoundException::class);
        $spaceId = 999; // Un ID que no existe

        $this->spaceRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($spaceId)
            ->andReturn(null); // El repositorio no encontró nada

        // Aseguramos que 'save' nunca sea llamado si no se encuentra el espacio
        $this->spaceRepositoryMock->shouldNotReceive('save');

        // Act
        $this->updateSpaceUseCase->execute($spaceId, 'Inexistente', 'salón', 10);
    }
}
