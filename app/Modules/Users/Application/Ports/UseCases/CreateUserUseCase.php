<?php

namespace App\Modules\Users\Application\UseCases;

use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Users\Domain\User;
use Illuminate\Validation\ValidationException; // Para lanzar excepciones de dominio

class CreateUserUseCase
{
    public function __construct(private UserRepositoryPort $userRepository) {}

    public function execute(string $name, string $email, string $password): User
    {
        // Validación de negocio: el email debe ser único.
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser) {
            // Lanzamos una excepción que el controlador puede capturar.
            throw ValidationException::withMessages([
               'email' => 'El correo electrónico ya está en uso.',
            ]);
        }

        $user = new User(null, $name, $email);

        return $this->userRepository->save($user, $password);
    }
}
