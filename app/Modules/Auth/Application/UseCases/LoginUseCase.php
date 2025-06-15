<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\Ports\AuthPort;
use App\Modules\Auth\Domain\AuthToken;
use Illuminate\Validation\ValidationException;

class LoginUseCase
{
    public function __construct(private AuthPort $authPort) {}

    public function execute(string $email, string $password): AuthToken
    {
        $authToken = $this->authPort->login($email, $password);

        if (!$authToken) {
            // Si el login falla, lanzamos una excepción de validación
            // que el controlador puede interpretar como un error 401 o 422.
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        return $authToken;
    }
}
