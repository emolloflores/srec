<?php

namespace Database\Factories;

use App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel>
 */
class SpaceFactory extends Factory
{
    /**
     * El modelo que corresponde a este factory.
     *
     * @var string
     */
    protected $model = SpaceEloquentModel::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // El array de tipos de espacio permitidos
        $spaceTypes = ['salón', 'auditorio', 'cancha'];

        return [
            // 'name': Genera un nombre de lugar ficticio, como "Kunde Hall" o "Moore Stream".
            // Para hacerlo más relevante, concatenamos con el tipo de espacio.
            'name' => $this->faker->words(2, true) . ' Room',

            // 'type': Elige aleatoriamente uno de los tipos definidos en el array.
            'type' => $this->faker->randomElement($spaceTypes),

            // 'capacity': Genera un número aleatorio entre 20 y 300.
            'capacity' => $this->faker->numberBetween(20, 300),
        ];
    }
}
