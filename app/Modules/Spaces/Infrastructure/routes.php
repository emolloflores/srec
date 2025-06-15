<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Spaces\Infrastructure\Http\Controllers\SpaceController;

// Usando `Route::apiResource` para generar las rutas CRUD estándar.
// GET      /api/spaces             -> index
// POST     /api/spaces             -> store
// GET      /api/spaces/{space}     -> show
// PUT      /api/spaces/{space}     -> update
// DELETE   /api/spaces/{space}     -> destroy
Route::apiResource('spaces', SpaceController::class);
