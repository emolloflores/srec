<?php

namespace App\Modules\Spaces\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Modules\Spaces\Application\UseCases\CreateSpaceUseCase;
use App\Modules\Spaces\Application\UseCases\DeleteSpaceUseCase;
use App\Modules\Spaces\Application\UseCases\GetSpaceUseCase;
use App\Modules\Spaces\Application\UseCases\ListSpacesUseCase;
use App\Modules\Spaces\Application\UseCases\UpdateSpaceUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SpaceController extends Controller
{
    public function __construct(
        private CreateSpaceUseCase $createSpaceUseCase,
        private GetSpaceUseCase $getSpaceUseCase,
        private ListSpacesUseCase $listSpacesUseCase,
        private UpdateSpaceUseCase $updateSpaceUseCase,
        private DeleteSpaceUseCase $deleteSpaceUseCase
    ) {}

    // GET /api/spaces
    public function index(): JsonResponse
    {
        $spaces = $this->listSpacesUseCase->execute();
        return response()->json($spaces);
    }

    // POST /api/spaces
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:salón,auditorio,cancha',
                'capacity' => 'required|integer|min:1',
            ]);

            $space = $this->createSpaceUseCase->execute($data['name'], $data['type'], $data['capacity']);

            return response()->json($space, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $e->errors()], 422);
        }
    }

    // GET /api/spaces/{id}
    public function show(int $id): JsonResponse
    {
        try {
            $space = $this->getSpaceUseCase->execute($id);
            return response()->json($space);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // PUT /api/spaces/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:salón,auditorio,cancha',
                'capacity' => 'required|integer|min:1',
            ]);

            $space = $this->updateSpaceUseCase->execute($id, $data['name'], $data['type'], $data['capacity']);

            return response()->json($space);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Datos inválidos', 'errors' => $e->errors()], 422);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    // DELETE /api/spaces/{id}
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteSpaceUseCase->execute($id);
            return response()->json(null, 204); // 204 No Content
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
