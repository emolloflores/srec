<?php

namespace App\Modules\Spaces\Application\UseCases;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Spaces\Domain\Space;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;

class GetSpaceUseCase
{
    public function __construct(private SpaceRepositoryPort $spaceRepository) {}

    public function execute(int $id): Space
    {
        $space = $this->spaceRepository->findById($id);

        if (!$space) {
            throw new EntityNotFoundException("El espacio con ID {$id} no fue encontrado.");
        }

        return $space;
    }
}
