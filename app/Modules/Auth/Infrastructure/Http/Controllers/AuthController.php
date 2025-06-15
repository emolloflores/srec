<?php

namespace App\Modules\Auth\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\UseCases\LoginUseCase;
use App\Modules\Auth\Application\UseCases\LogoutUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private LogoutUseCase $logoutUseCase
    ) {}

    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $authToken = $this->loginUseCase->execute($credentials['email'], $credentials['password']);

            // Devolvemos el token y los datos del usuario.
            return response()->json([
                'access_token' => $authToken->token,
                'token_type' => 'Bearer',
                'user' => $authToken->user,
            ]);

        } catch (ValidationException $e) {
            // Capturamos tanto la validación de la request como la del caso de uso.
            return response()->json(['message' => 'Credenciales inválidas.', 'errors' => $e->errors()], 422);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->logoutUseCase->execute();

        return response()->json(['message' => 'Sesión cerrada exitosamente.']);
    }
}
