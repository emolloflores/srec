<?php

namespace App\Modules\Auth\Infrastructure\Adapters;

use App\Modules\Auth\Application\Ports\AuthPort;
use App\Modules\Auth\Domain\AuthToken;
use App\Modules\Users\Domain\User as UserEntity;
use App\Modules\Users\Infrastructure\Persistence\Eloquent\UserEloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SanctumAuthAdapter implements AuthPort
{
    public function login(string $email, string $password): ?AuthToken
    {
        // Usamos el guard 'web' de Laravel para validar las credenciales.
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return null;
        }

        // Si las credenciales son válidas, obtenemos el modelo de Eloquent.
        /** @var UserEloquentModel $eloquentUser */
        $eloquentUser = Auth::user();

        // Creamos un token de API de Sanctum.
        $token = $eloquentUser->createToken('api-token')->plainTextToken;

        // Mapeamos el modelo de Eloquent a nuestra entidad de dominio.
        $userEntity = new UserEntity(
            $eloquentUser->id,
            $eloquentUser->name,
            $eloquentUser->email
        );

        // Devolvemos nuestro objeto de dominio AuthToken.
        return new AuthToken($token, $userEntity);
    }

    public function logout(): void
    {
        // Obtenemos el usuario autenticado a través de la request actual
        // y revocamos su token actual.
        /** @var UserEloquentModel $user */
        $user = Request::user();

        if ($user) {
            $user->user()->currentAccessToken()->delete();

            //$user->currentAccessToken()->delete();
        }
    }
}
