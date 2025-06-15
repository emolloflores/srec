<?php

namespace App\Modules\Users\Application\UseCases;

use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Users\Domain\User;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;

class GetUserUseCase
{
    public function __construct(private UserRepositoryPort $userRepository) {}

    public function execute(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new EntityNotFoundException("El usuario con ID {$id} no fue encontrado.");
        }

        return $user;
    }
}
