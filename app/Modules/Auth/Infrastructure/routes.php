<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Infrastructure\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// La ruta de logout debe estar protegida por el middleware de autenticación 'sanctum'.
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
