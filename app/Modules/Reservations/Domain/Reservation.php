<?php
namespace App\Modules\Reservations\Domain;

use Carbon\Carbon;

class Reservation
{
    public ?int $id;
    public int $userId;
    public int $spaceId;
    public Carbon $startTime;
    public Carbon $endTime;
    public string $status;

    public function __construct(
        ?int $id,
        int $userId,
        int $spaceId,
        Carbon $startTime,
        Carbon $endTime,
        string $status = 'confirmed'
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->spaceId = $spaceId;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->status = $status;
    }
}
