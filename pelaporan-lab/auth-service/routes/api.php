<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public Auth Endpoints
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-token', [AuthController::class, 'verifyToken']);

// Protected Auth Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
