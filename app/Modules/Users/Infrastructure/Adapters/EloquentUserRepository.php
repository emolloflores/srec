<?php
namespace App\Modules\Users\Infrastructure\Adapters;

use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Users\Domain\User as UserEntity;
use App\Modules\Users\Infrastructure\Persistence\Eloquent\UserEloquentModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepositoryPort
{
    public function save(UserEntity $user, ?string $password = null): UserEntity
    {
        $eloquentUser = UserEloquentModel::findOrNew($user->id);

        $eloquentUser->name = $user->name;
        $eloquentUser->email = $user->email;

        // Solo actualizamos la contraseña si se proporciona una nueva.
        if ($password) {
            $eloquentUser->password = Hash::make($password);
        }

        $eloquentUser->save();

        $user->id = $eloquentUser->id;

        return $user;
    }
    public function findById(int $id): ?UserEntity
    {
        $eloquentUser = UserEloquentModel::find($id);
        return $eloquentUser ? $this->toEntity($eloquentUser) : null;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $eloquentUser = UserEloquentModel::where('email', $email)->first();
        return $eloquentUser ? $this->toEntity($eloquentUser) : null;
    }

    public function findAll(): Collection
    {
        return UserEloquentModel::all()->map(fn($eloquentUser) => $this->toEntity($eloquentUser));
    }

    private function toEntity(UserEloquentModel $eloquentUser): UserEntity
    {
        return new UserEntity(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->email
        );
    }
}
