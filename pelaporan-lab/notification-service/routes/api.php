<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notification Service API Routes (Port 8005)
|--------------------------------------------------------------------------
*/

Route::get('/health', fn () => response()->json(['service' => 'notification-service', 'status' => 'healthy', 'port' => 8005]));

// Log Riwayat Notifikasi
Route::get('/notifications/logs', [NotificationController::class, 'getLogs']);

// 1. Notifikasi laporan baru dibuat (ke Admin)
Route::post('/notifications/report-created', [NotificationController::class, 'sendReportCreated']);

// 2. Notifikasi penugasan teknisi (ke Teknisi & Mahasiswa)
Route::post('/notifications/technician-assigned', [NotificationController::class, 'sendTechnicianAssigned']);

// 3. Notifikasi perubahan status laporan (ke Mahasiswa)
Route::post('/notifications/status-changed', [NotificationController::class, 'sendStatusChanged']);
