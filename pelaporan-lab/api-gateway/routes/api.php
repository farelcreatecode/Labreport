<?php

use App\Http\Controllers\GatewayController;
use Illuminate\Support\Facades\Route;

// Health check Gateway
Route::get('/health', function () {
    return response()->json([
        'gateway' => 'API Gateway',
        'status'  => 'healthy',
        'port'    => 8001,
        'routes'  => [
            'auth'         => env('AUTH_SERVICE_URL', 'http://127.0.0.1:8002'),
            'report'       => env('REPORT_SERVICE_URL', 'http://127.0.0.1:8003'),
            'notification' => env('NOTIFICATION_SERVICE_URL', 'http://127.0.0.1:8005'),
        ],
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Proxy ke Auth Service
Route::any('/auth/{any?}', function (Illuminate\Http\Request $request, $any = '') {
    return app(GatewayController::class)->forwardAuth($request, $any);
})->where('any', '.*');

// Proxy ke Report Service (Public & Admin)
Route::any('/public/{any?}', function (Illuminate\Http\Request $request, $any = '') {
    return app(GatewayController::class)->forwardReport($request, 'public/' . $any);
})->where('any', '.*');

Route::any('/admin/{any?}', function (Illuminate\Http\Request $request, $any = '') {
    return app(GatewayController::class)->forwardReport($request, 'admin/' . $any);
})->where('any', '.*');

// Proxy ke Notification Service
Route::any('/notifications/{any?}', function (Illuminate\Http\Request $request, $any = '') {
    return app(GatewayController::class)->forwardNotification($request, 'notifications/' . $any);
})->where('any', '.*');
