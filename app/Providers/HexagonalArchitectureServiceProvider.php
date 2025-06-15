<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Puertos
use App\Modules\Notifications\Application\Ports\NotificationPort;
use App\Modules\Reservations\Application\Ports\ReservationRepositoryPort;
use App\Modules\Spaces\Application\Ports\SpaceRepositoryPort;
use App\Modules\Users\Application\Ports\UserRepositoryPort;
use App\Modules\Auth\Application\Ports\AuthPort;


// Adaptadores
use App\Modules\Notifications\Infrastructure\Adapters\LogNotificationAdapter;
use App\Modules\Reservations\Infrastructure\Adapters\EloquentReservationRepository;
use App\Modules\Spaces\Infrastructure\Adapters\EloquentSpaceRepository;
use App\Modules\Users\Infrastructure\Adapters\EloquentUserRepository;
use App\Modules\Auth\Infrastructure\Adapters\SanctumAuthAdapter;


class HexagonalArchitectureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationPort::class, LogNotificationAdapter::class);
        $this->app->bind(ReservationRepositoryPort::class, EloquentReservationRepository::class);
        $this->app->bind(SpaceRepositoryPort::class, EloquentSpaceRepository::class);
        $this->app->bind(UserRepositoryPort::class, EloquentUserRepository::class);
        $this->app->bind(AuthPort::class, SanctumAuthAdapter::class);

    }

    public function boot(): void
    {
        //
    }
}
