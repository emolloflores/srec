<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Users\Infrastructure\Http\Controllers\UserController;

// apiResource no incluye create ni edit, que son para vistas.
// Aquí excluimos 'destroy' porque no lo hemos implementado.
Route::apiResource('users', UserController::class)->except(['destroy']);
