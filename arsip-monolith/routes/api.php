<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Health check endpoint for microservice monitoring / Docker / Kubernetes probes.
 */
Route::get('/health', function () {
    $dbOk = false;
    try {
        DB::connection()->getPdo();
        $dbOk = true;
    } catch (\Throwable $e) {}

    return response()->json([
        'status'    => $dbOk ? 'healthy' : 'unhealthy',
        'service'   => 'lab-complaint-service',
        'timestamp' => now()->toIso8601String(),
        'checks'    => [
            'database' => $dbOk ? 'connected' : 'disconnected',
            'storage'  => is_writable(storage_path()) ? 'writable' : 'not_writable',
        ],
    ], $dbOk ? 200 : 503);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * UNUJA Portal Data Lookup endpoint (Consumed by frontend or external services).
 */
Route::get('/portal/lookup', function (Request $request, \App\Services\UnujaPortalService $portalService) {
    $filter = $request->query('filter', 'mahasiswa');
    $search = $request->query('search', '');
    $data   = $portalService->getReferenceData($filter, $search);

    return response()->json($data);
});
