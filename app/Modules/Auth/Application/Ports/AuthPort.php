<?php

namespace App\Modules\Auth\Application\Ports;

use App\Modules\Auth\Domain\AuthToken;

interface AuthPort
{
    /**
     * Intenta autenticar a un usuario con sus credenciales.
     * Devuelve un AuthToken si tiene éxito, null en caso contrario.
     */
    public function login(string $email, string $password): ?AuthToken;

    /**
     * Cierra la sesión del usuario autenticado actualmente.
     */
    public function logout(): void;
}
