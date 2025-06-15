<?php

namespace App\Modules\Users\Application\UseCases;

use App\Modules\Users\Application\Ports\UserRepositoryPort;
use Illuminate\Support\Collection;

class ListUsersUseCase
{
    public function __construct(private UserRepositoryPort $userRepository) {}

    public function execute(): Collection
    {
        return $this->userRepository->findAll();
    }
}
