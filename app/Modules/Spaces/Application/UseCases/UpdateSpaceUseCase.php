<?php

namespace App\Modules\Spaces\Application\UseCases;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Spaces\Domain\Space;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;

class UpdateSpaceUseCase
{
    public function __construct(private SpaceRepositoryPort $spaceRepository) {}

    public function execute(int $id, string $name, string $type, int $capacity): Space
    {
        $space = $this->spaceRepository->findById($id);

        if (!$space) {
            throw new EntityNotFoundException("No se puede actualizar. El espacio con ID {$id} no fue encontrado.");
        }

        // Actualizamos las propiedades de la entidad de dominio existente
        $space->name = $name;
        $space->type = $type;
        $space->capacity = $capacity;

        // El método save se encargará de la lógica de actualización
        return $this->spaceRepository->save($space);
    }
}
