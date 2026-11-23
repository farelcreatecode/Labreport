<?php

use App\Http\Controllers\PelaporController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Frontend Portal Pelapor (Port 8000)
|--------------------------------------------------------------------------
| Portal Publik khusus untuk mahasiswa / pelapor tanpa login.
| Menampilkan form pelaporan fasilitas lab, unggah foto, dan pelacakan tiket.
*/

// Halaman Formulir Pelaporan Publik (Pelapor Tanpa Login)
Route::get('/', [PelaporController::class, 'create'])->name('home');
Route::get('/lapor', [PelaporController::class, 'create'])->name('pelapor.create');
Route::post('/lapor', [PelaporController::class, 'store'])->name('pelapor.store');

// Halaman Pelacakan Tiket & Riwayat Hasil Pengaduan
Route::get('/lacak', [PelaporController::class, 'track'])->name('pelapor.track');
Route::get('/hasil-laporan', [PelaporController::class, 'track'])->name('pelapor.results');

// Redirect login ke Portal Admin di Port 8004 jika ada yang mengakses /login
Route::get('/login', function () {
    return redirect(config('app.admin_url', 'http://127.0.0.1:8004/login'));
});
Route::get('/admin', function () {
    return redirect(config('app.admin_url', 'http://127.0.0.1:8004/login'));
});
