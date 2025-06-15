<?php

namespace App\Modules\Spaces\Application\UseCases;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Spaces\Domain\Space;

class CreateSpaceUseCase
{
    public function __construct(private SpaceRepositoryPort $spaceRepository) {}

    public function execute(string $name, string $type, int $capacity): Space
    {
        // Aquí podrían ir validaciones de negocio, por ejemplo,
        // que no se pueda crear un espacio con un nombre ya existente.

        $space = new Space(null, $name, $type, $capacity);

        return $this->spaceRepository->save($space);
    }
}
