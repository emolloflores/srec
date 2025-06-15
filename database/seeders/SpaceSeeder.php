<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel;


class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SpaceEloquentModel::create([
            'name' => 'Salón Principal',
            'type' => 'salón',
            'capacity' => 100
        ]);
        SpaceEloquentModel::create([
            'name' => 'Auditorio Central',
            'type' => 'auditorio',
            'capacity' => 250
        ]);
        SpaceEloquentModel::create([
            'name' => 'Cancha de Baloncesto',
            'type' => 'cancha',
            'capacity' => 50
        ]);
    }
}
