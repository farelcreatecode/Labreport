<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminComplaintController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMasterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Frontend Portal Admin (Port 8004)
|--------------------------------------------------------------------------
| Portal Backoffice khusus Administrator & Teknisi Lab Komputer.
| Memerlukan login kredensial dan verifikasi Bearer Token dari Auth Service.
*/

// Redirect root ke login atau dashboard
Route::get('/', function () {
    if (session()->has('admin_token')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Autentikasi Admin
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// ========================================================
// ADMIN AREA (Wajib Login & Memiliki Token Admin)
// ========================================================
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Laporan
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{id}', [AdminComplaintController::class, 'update'])->name('complaints.update');

    // Master Laboratorium
    Route::get('/labs', [AdminMasterController::class, 'laboratories'])->name('labs.index');
    Route::get('/labs/create', [AdminMasterController::class, 'createLaboratory'])->name('labs.create');
    Route::post('/labs', [AdminMasterController::class, 'storeLaboratory'])->name('labs.store');
    Route::get('/labs/{id}/edit', [AdminMasterController::class, 'editLaboratory'])->name('labs.edit');
    Route::put('/labs/{id}', [AdminMasterController::class, 'updateLaboratory'])->name('labs.update');
    Route::delete('/labs/{id}', [AdminMasterController::class, 'destroyLaboratory'])->name('labs.destroy');

    // Master Fasilitas
    Route::get('/facilities', [AdminMasterController::class, 'facilities'])->name('facilities.index');
    Route::get('/facilities/create', [AdminMasterController::class, 'createFacility'])->name('facilities.create');
    Route::post('/facilities', [AdminMasterController::class, 'storeFacility'])->name('facilities.store');
    Route::get('/facilities/{id}/edit', [AdminMasterController::class, 'editFacility'])->name('facilities.edit');
    Route::put('/facilities/{id}', [AdminMasterController::class, 'updateFacility'])->name('facilities.update');
    Route::delete('/facilities/{id}', [AdminMasterController::class, 'destroyFacility'])->name('facilities.destroy');

    // Master Teknisi (Pekerja Lapangan, Tanpa Akun Login)
    Route::get('/technicians', [AdminMasterController::class, 'technicians'])->name('technicians.index');
    Route::get('/technicians/create', [AdminMasterController::class, 'createTechnician'])->name('technicians.create');
    Route::post('/technicians', [AdminMasterController::class, 'storeTechnician'])->name('technicians.store');
    Route::get('/technicians/{id}/edit', [AdminMasterController::class, 'editTechnician'])->name('technicians.edit');
    Route::put('/technicians/{id}', [AdminMasterController::class, 'updateTechnician'])->name('technicians.update');
    Route::delete('/technicians/{id}', [AdminMasterController::class, 'destroyTechnician'])->name('technicians.destroy');

    // Statistik Laporan
    Route::get('/statistics', [AdminMasterController::class, 'statistics'])->name('statistics.index');
});
