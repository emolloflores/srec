<?php

namespace App\Modules\Users\Application\UseCases;

use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Users\Domain\User;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use Illuminate\Validation\ValidationException;

class UpdateUserUseCase
{
    public function __construct(private UserRepositoryPort $userRepository) {}

    public function execute(int $id, string $name, string $email, ?string $password = null): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new EntityNotFoundException("No se puede actualizar. El usuario con ID {$id} no fue encontrado.");
        }

        // Validación de negocio: si el email cambia, debe seguir siendo único.
        if ($user->email !== $email) {
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser) {
                throw ValidationException::withMessages([
                    'email' => 'El correo electrónico ya está en uso por otro usuario.',
                ]);
            }
        }

        $user->name = $name;
        $user->email = $email;

        // El método save se encarga de la lógica de actualización, incluyendo la contraseña si se proporciona.
        return $this->userRepository->save($user, $password);
    }
}
