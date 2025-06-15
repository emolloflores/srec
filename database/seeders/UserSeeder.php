<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Users\Infrastructure\Persistence\Eloquent\UserEloquentModel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        UserEloquentModel::create([
            'name' => 'Usuario de Prueba',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

    }
}
