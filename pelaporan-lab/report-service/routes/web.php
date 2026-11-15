<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'service'   => 'Report Service',
        'status'    => 'running',
        'port'      => 8003,
        'timestamp' => now()->toIso8601String(),
    ]);
});
