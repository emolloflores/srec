<?php
// namespace App\Modules\Reservations\Application\Ports;

// use App\Modules\Reservations\Domain\Reservation;
// use Carbon\Carbon;

// interface ReservationRepositoryPort
// {
//     public function save(Reservation $reservation): Reservation;
//     public function findBySpaceAndDate(int $spaceId, Carbon $startTime, Carbon $endTime): ?Reservation;
// }


namespace App\Modules\Reservations\Application\Ports;

use App\Modules\Reservations\Domain\Reservation;
use Carbon\Carbon;

interface ReservationRepositoryPort
{
    public function save(Reservation $reservation): Reservation;
    public function findOverlapping(int $spaceId, Carbon $startTime, Carbon $endTime): ?Reservation;
}
