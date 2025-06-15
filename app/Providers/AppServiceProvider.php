<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory; // <-- Importante
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
         // Aquí está la magia. Le damos a Laravel una nueva forma de "adivinar"
        // los nombres de los factories.
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            // Ejemplo:
            // $modelName será: App\Modules\Spaces\Infrastructure\Persistence\Eloquent\SpaceEloquentModel

            // 1. Reemplazamos la parte inicial del namespace del modelo
            // 'App\Modules' por 'Database\Factories'
            $factoryNamespace = 'Database\\Factories\\' . substr($modelName, strlen('App\\Modules\\'));

            // 2. Quitamos las partes intermedias que no queremos ('Infrastructure\Persistence\Eloquent')
            $factoryNamespace = str_replace(
                ['Infrastructure\\Persistence\\Eloquent\\'],
                '',
                $factoryNamespace
            );

            // 3. Añadimos 'Factory' al final del nombre de la clase
            // El resultado final será: Database\Factories\Spaces\SpaceEloquentModelFactory
            // ¡Esto sigue siendo incorrecto! Nuestra estructura es más simple.

            // --- VAMOS A SIMPLIFICAR LA LÓGICA ---

            // Tomamos solo el nombre de la clase del modelo (ej. "SpaceEloquentModel")
            $modelClassName = class_basename($modelName);

            // Le quitamos la palabra "EloquentModel" para obtener el nombre base (ej. "Space")
            $baseName = str_replace('EloquentModel', '', $modelClassName);

            // Construimos el FQN (Fully Qualified Name) del factory que SÍ existe
            // Resultado: "Database\Factories\SpaceFactory"
            return 'Database\\Factories\\' . $baseName . 'Factory';
        });
    }
}
