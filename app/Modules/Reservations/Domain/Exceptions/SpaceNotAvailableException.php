<?php
namespace App\Modules\Reservations\Domain\Exceptions;

use App\Modules\Shared\Domain\Exceptions\DomainException;

class SpaceNotAvailableException extends DomainException
{
    public function __construct(string $spaceName, string $date)
    {
        parent::__construct("El espacio '{$spaceName}' no está disponible en la fecha '{$date}'.");
    }
}
