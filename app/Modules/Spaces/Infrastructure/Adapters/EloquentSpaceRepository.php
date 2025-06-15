<?php

namespace App\Modules\Spaces\Infrastructure\Adapters;

use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Spaces\Domain\Space as SpaceEntity;
use App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel;
use Illuminate\Support\Collection;


class EloquentSpaceRepository implements SpaceRepositoryPort
{
    public function save(SpaceEntity $space): SpaceEntity
    {
        // El método findOrNew maneja tanto la creación como la actualización.
        $eloquentSpace = SpaceEloquentModel::findOrNew($space->id);

        $eloquentSpace->name = $space->name;
        $eloquentSpace->type = $space->type;
        $eloquentSpace->capacity = $space->capacity;

        $eloquentSpace->save();

        // Actualizamos la entidad con el ID generado por la BD si es nuevo
        $space->id = $eloquentSpace->id;

        return $space;
    }

    public function findById(int $id): ?SpaceEntity
    {
        $eloquentSpace = SpaceEloquentModel::find($id);

        if (!$eloquentSpace) {
            return null;
        }

        return $this->toEntity($eloquentSpace);
    }

    public function findAll(): Collection
    {
        return SpaceEloquentModel::all()->map(function ($eloquentSpace) {
            return $this->toEntity($eloquentSpace);
        });
    }

    public function deleteById(int $id): bool
    {
        $eloquentSpace = SpaceEloquentModel::find($id);
        if ($eloquentSpace) {
            return $eloquentSpace->delete();
        }
        return false;
    }

    private function toEntity(SpaceEloquentModel $eloquentSpace): SpaceEntity
    {
        return new SpaceEntity(
            $eloquentSpace->id,
            $eloquentSpace->name,
            $eloquentSpace->type,
            $eloquentSpace->capacity
        );
    }
}
