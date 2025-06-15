<?php

namespace App\Modules\Auth\Domain;

use App\Modules\Users\Domain\User;

class AuthToken
{
    public function __construct(
        public string $token,
        public User $user
    ) {}
}
