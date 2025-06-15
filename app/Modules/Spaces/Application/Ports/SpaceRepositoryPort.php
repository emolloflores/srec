<?php

namespace App\Modules\Spaces\Application\Ports;

use App\Modules\Spaces\Domain\Space;
use Illuminate\Support\Collection;


interface SpaceRepositoryPort
{
    public function save(Space $space): Space;
    public function findById(int $id): ?Space;
    public function findAll(): Collection;
    public function deleteById(int $id): bool;


}
