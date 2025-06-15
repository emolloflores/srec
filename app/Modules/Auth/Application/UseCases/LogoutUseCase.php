<?php

namespace App\Modules\Auth\Application\UseCases;

use App\Modules\Auth\Application\Ports\AuthPort;

class LogoutUseCase
{
    public function __construct(private AuthPort $authPort) {}

    public function execute(): void
    {
        $this->authPort->logout();
    }
}
