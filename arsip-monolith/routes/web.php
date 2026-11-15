<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\LabController as AdminLabController;
use App\Http\Controllers\Admin\StatisticController as AdminStatisticController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Pelapor\ComplaintController as PelaporComplaintController;
use App\Http\Controllers\Pelapor\DashboardController as PelaporDashboardController;
use App\Http\Controllers\Pelapor\FacilityController as PelaporFacilityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect based on role or to login
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return in_array($role, ['admin', 'teknisi'], true)
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// --------------------------------------------------------------------------
// Dashboard Route with Smart Role-Based Redirect
// --------------------------------------------------------------------------
Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    if (in_array($request->user()->role, ['admin', 'teknisi'], true)) {
        return redirect()->route('admin.dashboard');
    }
    return app(PelaporDashboardController::class)->index($request);
})->middleware('auth')->name('dashboard');

// --------------------------------------------------------------------------
// Pelapor Routes (Mahasiswa & Dosen)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:mahasiswa,dosen'])->group(function () {
    // Pelapor Complaints (Buat & Riwayat Laporan)
    Route::resource('complaints', PelaporComplaintController::class)->only([
        'index', 'create', 'store', 'show',
    ]);

    // Pelapor Data Fasilitas
    Route::get('/facilities', [PelaporFacilityController::class, 'index'])->name('facilities.index');
});

// --------------------------------------------------------------------------
// Admin & Teknisi Shared Routes
// --------------------------------------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,teknisi'])->group(function () {
    // Admin / Teknisi Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Laporan
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{complaint}', [AdminComplaintController::class, 'update'])->name('complaints.update');

    // Statistik Laporan
    Route::get('/statistics', [AdminStatisticController::class, 'index'])->name('statistics.index');

    // ----------------------------------------------------------------------
    // Admin ONLY Management (CRUD: Labs, Categories, Facilities, Users)
    // ----------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        Route::resource('labs', AdminLabController::class);
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('facilities', AdminFacilityController::class);
        Route::resource('users', AdminUserController::class);
    });
});

// --------------------------------------------------------------------------
// Profile Management
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
