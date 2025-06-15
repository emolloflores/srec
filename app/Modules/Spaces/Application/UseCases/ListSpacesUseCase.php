<?php

namespace App\Modules\Spaces\Application\UseCases;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use Illuminate\Support\Collection;

class ListSpacesUseCase
{
    public function __construct(private SpaceRepositoryPort $spaceRepository) {}

    public function execute(): Collection
    {
        return $this->spaceRepository->findAll();
    }
}
