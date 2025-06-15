<?php
namespace App\Modules\Users\Application\Ports;
use Illuminate\Support\Collection;
use App\Modules\Users\Domain\User;

interface UserRepositoryPort {
    /**
     * Guarda un usuario (creación/actualización).
     */
    public function save(User $user, ?string $password = null): User;

    /**
     * Busca un usuario por su ID.
     */
    public function findById(int $id): ?User;

    /**
     * Busca un usuario por su email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Obtiene todos los usuarios.
     */
    public function findAll(): Collection;
}
