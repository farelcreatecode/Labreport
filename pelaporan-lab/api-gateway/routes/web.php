<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'gateway' => 'API Gateway - Sistem Pelaporan Lab',
        'status'  => 'active',
        'port'    => 8001,
        'endpoints' => [
            'auth'   => '/api/auth/*',
            'public' => '/api/public/*',
            'admin'  => '/api/admin/*',
        ],
    ]);
});
