<?php

namespace App\Modules\Spaces\Application\UseCases;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;

class DeleteSpaceUseCase
{
    public function __construct(private SpaceRepositoryPort $spaceRepository) {}

    public function execute(int $id): void
    {
        $wasDeleted = $this->spaceRepository->deleteById($id);

        if (!$wasDeleted) {
            // Esto puede ocurrir si el ID no existe
            throw new EntityNotFoundException("No se puede eliminar. El espacio con ID {$id} no fue encontrado.");
        }
    }
}
