<?php
namespace App\Modules\Reservations\Infrastructure\Adapters;

use App\Modules\Reservations\Application\Ports\ReservationRepositoryPort;
use App\Modules\Reservations\Domain\Reservation as ReservationEntity;
use App\Modules\Reservations\Infrastructure\Persistence\Eloquent\ReservationEloquentModel;
use Carbon\Carbon;

class EloquentReservationRepository implements ReservationRepositoryPort
{
    public function save(ReservationEntity $reservation): ReservationEntity
    {
        // Mapeo de Entidad a Modelo Eloquent
        $eloquentReservation = ReservationEloquentModel::findOrNew($reservation->id);
        $eloquentReservation->user_id = $reservation->userId;
        $eloquentReservation->space_id = $reservation->spaceId;
        $eloquentReservation->start_time = $reservation->startTime;
        $eloquentReservation->end_time = $reservation->endTime;
        $eloquentReservation->status = $reservation->status;

        $eloquentReservation->save();

        // Actualizar el ID en la entidad de dominio si es nueva
        $reservation->id = $eloquentReservation->id;

        return $reservation;
    }

    public function findOverlapping(int $spaceId, Carbon $startTime, Carbon $endTime): ?ReservationEntity
    {
        $eloquentReservation = ReservationEloquentModel::where('space_id', $spaceId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->first();

        if (!$eloquentReservation) {
            return null;
        }

        // Mapeo de Eloquent a Entidad de Dominio
        return new ReservationEntity(
            $eloquentReservation->id,
            $eloquentReservation->user_id,
            $eloquentReservation->space_id,
            $eloquentReservation->start_time,
            $eloquentReservation->end_time,
            $eloquentReservation->status
        );
    }
}
