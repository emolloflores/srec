<?php

namespace App\Modules\Reservations\Application\UseCases;

use App\Modules\Notifications\Application\Ports\NotificationPort;
use App\Modules\Reservations\Application\Ports\ReservationRepositoryPort;
use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Reservations\Domain\Reservation as ReservationEntity;
use App\Modules\Shared\Domain\Exceptions\EntityNotFoundException;
use App\Modules\Reservations\Domain\Exceptions\SpaceNotAvailableException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateReservationUseCase
{
    public function __construct(
        private ReservationRepositoryPort $reservationRepository,
        private UserRepositoryPort $userRepository,
        private SpaceRepositoryPort $spaceRepository,
        private NotificationPort $notificationPort
    ) {}

    public function execute(int $userId, int $spaceId, string $startTimeStr, int $durationHours): ReservationEntity
    {
        $startTime = Carbon::parse($startTimeStr);
        $endTime = $startTime->copy()->addHours($durationHours);

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new EntityNotFoundException("El usuario con ID {$userId} no existe.");
        }

        $space = $this->spaceRepository->findById($spaceId);
        if (!$space) {
            throw new EntityNotFoundException("El espacio con ID {$spaceId} no existe.");
        }

        return DB::transaction(function () use ($user, $space, $startTime, $endTime) {
            $existingReservation = $this->reservationRepository->findOverlapping($space->id, $startTime, $endTime);
            if ($existingReservation) {
                throw new SpaceNotAvailableException($space->name, $startTime->toDateTimeString());
            }

            $reservation = new ReservationEntity(null, $user->id, $space->id, $startTime, $endTime);
            $savedReservation = $this->reservationRepository->save($reservation);

            $this->notificationPort->send(
                $user->email,
                'Confirmación de Reserva',
                "Hola {$user->name}, tu reserva para el espacio '{$space->name}' desde {$startTime->format('Y-m-d H:i')} hasta {$endTime->format('Y-m-d H:i')} ha sido confirmada."
            );

            return $savedReservation;
        });
    }
}
