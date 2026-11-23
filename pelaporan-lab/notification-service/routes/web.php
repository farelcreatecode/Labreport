<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'service' => 'Auth Service',
        'status'  => 'running',
        'port'    => 8002,
        'timestamp' => now()->toIso8601String(),
    ]);
});
