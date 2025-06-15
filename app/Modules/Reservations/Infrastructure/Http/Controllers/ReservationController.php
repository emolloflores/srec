<?php

namespace App\Modules\Reservations\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reservations\Application\UseCases\CreateReservationUseCase;
use App\Modules\Reservations\Domain\Exceptions\SpaceNotAvailableException;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function __construct(private CreateReservationUseCase $createReservationUseCase) {}

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'space_id' => 'required|integer|exists:spaces,id',
                'start_time' => 'required|date_format:Y-m-d H:i:s|after:now',
                'duration_hours' => 'required|integer|min:1',
            ]);

            $reservation = $this->createReservationUseCase->execute(
                $validatedData['user_id'],
                $validatedData['space_id'],
                $validatedData['start_time'],
                $validatedData['duration_hours']
            );

            return response()->json($reservation, 201);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Datos de entrada inválidos.', 'errors' => $e->errors()], 422);
        } catch (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (SpaceNotAvailableException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Illuminate\Support\Facades\Log::error($e);
            return response()->json(['message' => 'Ha ocurrido un error inesperado en el servidor.'], 500);
        }
    }
}
