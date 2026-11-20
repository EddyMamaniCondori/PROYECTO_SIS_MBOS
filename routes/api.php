<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes — MBOS Aplicación Móvil
|--------------------------------------------------------------------------
*/

// Rutas públicas (sin token)
Route::post('/login', [AuthApiController::class, 'login']);

// Rutas protegidas (requieren Bearer token de Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me',      [AuthApiController::class, 'me']);
});
