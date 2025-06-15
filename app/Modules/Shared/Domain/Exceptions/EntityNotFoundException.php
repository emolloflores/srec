<?php
namespace App\Modules\Shared\Domain\Exceptions;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $message = "La entidad solicitada no fue encontrada.", int $code = 404, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
