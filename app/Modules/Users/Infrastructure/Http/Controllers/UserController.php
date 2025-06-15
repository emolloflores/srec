<?php

namespace App\Modules\Users\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Modules\Users\Application\UseCases\CreateUserUseCase;
use App\Modules\Users\Application\UseCases\GetUserUseCase;
use App\Modules\Users\Application\UseCases\ListUsersUseCase;
use App\Modules\Users\Application\UseCases\UpdateUserUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private GetUserUseCase $getUserUseCase,
        private ListUsersUseCase $listUsersUseCase,
        private UpdateUserUseCase $updateUserUseCase
    ) {}

    public function index(): JsonResponse
    {
        $users = $this->listUsersUseCase->execute();
        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = $this->createUserUseCase->execute($data['name'], $data['email'], $data['password']);

            return response()->json($user, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $e->errors()], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->getUserUseCase->execute($id);
            return response()->json($user);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            // La validación `unique` ignora el email del usuario actual.
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8', // La contraseña es opcional al actualizar
            ]);

            $user = $this->updateUserUseCase->execute($id, $data['name'], $data['email'], $data['password'] ?? null);

            return response()->json($user);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $e->errors()], 422);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
