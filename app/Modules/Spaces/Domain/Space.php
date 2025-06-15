<?php
namespace App\Modules\Spaces\Domain;

class Space
{
    public ?int $id;
    public string $name;
    public string $type;
    public int $capacity;

    public function __construct(?int $id, string $name, string $type, int $capacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->capacity = $capacity;
    }
}
