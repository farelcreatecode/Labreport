<?php

use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\PublicReportController;
use Illuminate\Support\Facades\Route;

// ========================================================
// PUBLIC ROUTES (Pelapor tanpa login)
// ========================================================
Route::prefix('public')->group(function () {
    Route::get('/form-data', [PublicReportController::class, 'getFormData']);
    Route::post('/reports', [PublicReportController::class, 'store']);
    Route::get('/reports/{kode}', [PublicReportController::class, 'showPublic']);
    Route::get('/recent-results', [PublicReportController::class, 'recentResults']);
});

// ========================================================
// ADMIN PROTECTED ROUTES (Verifikasi Bearer Token ke Auth Service)
// ========================================================
Route::prefix('admin')->middleware('admin.token')->group(function () {
    // Dashboard & Reports
    Route::get('/dashboard', [AdminReportController::class, 'dashboard']);
    Route::get('/reports', [AdminReportController::class, 'index']);
    Route::get('/reports/{id}', [AdminReportController::class, 'show']);
    Route::put('/reports/{id}/status', [AdminReportController::class, 'updateStatus']);

    // Master Laboratories
    Route::get('/laboratories', [AdminMasterController::class, 'listLaboratories']);
    Route::post('/laboratories', [AdminMasterController::class, 'storeLaboratory']);
    Route::put('/laboratories/{id}', [AdminMasterController::class, 'updateLaboratory']);
    Route::delete('/laboratories/{id}', [AdminMasterController::class, 'destroyLaboratory']);

    // Master Facilities
    Route::get('/facilities', [AdminMasterController::class, 'listFacilities']);
    Route::post('/facilities', [AdminMasterController::class, 'storeFacility']);
    Route::put('/facilities/{id}', [AdminMasterController::class, 'updateFacility']);
    Route::delete('/facilities/{id}', [AdminMasterController::class, 'destroyFacility']);

    // Master Technicians
    Route::get('/technicians', [AdminMasterController::class, 'listTechnicians']);
    Route::post('/technicians', [AdminMasterController::class, 'storeTechnician']);
    Route::put('/technicians/{id}', [AdminMasterController::class, 'updateTechnician']);
    Route::delete('/technicians/{id}', [AdminMasterController::class, 'destroyTechnician']);
});
