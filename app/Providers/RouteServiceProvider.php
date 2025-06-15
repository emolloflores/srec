<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // Carga las rutas API por defecto de Laravel
            // Route::middleware('api')
            //     ->prefix('api')
            //     ->group(base_path('routes/api.php'));

            // Carga las rutas web por defecto de Laravel
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // *** NUESTRA LÓGICA MODULAR ***
            $this->bootModuleRoutes();
        });
    }

    protected function bootModuleRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Auth/Infrastructure/routes.php'));
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Reservations/Infrastructure/routes.php'));

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Spaces/Infrastructure/routes.php'));
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('app/Modules/Users/Infrastructure/routes.php'));
    }
}
