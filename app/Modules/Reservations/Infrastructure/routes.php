<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Reservations\Infrastructure\Http\Controllers\ReservationController;

Route::post('/reservations', [ReservationController::class, 'store']);
