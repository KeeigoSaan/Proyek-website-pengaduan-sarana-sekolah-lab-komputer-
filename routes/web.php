<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AduanController;

// Public / Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('siswa.dashboard');
    })->name('dashboard');

    // Siswa Routes (Hanya untuk Siswa)
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [AduanController::class, 'siswaDashboard'])->name('dashboard');
        Route::get('/aduan/create', [AduanController::class, 'create'])->name('aduan.create');
        Route::post('/aduan', [AduanController::class, 'store'])->name('aduan.store');
    });

    // Admin Routes (Hanya untuk Admin/Teknisi)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AduanController::class, 'adminDashboard'])->name('dashboard');
        Route::post('/aduan/{id}/status', [AduanController::class, 'updateStatus'])->name('aduan.status');
        Route::get('/laporan/excel', [AduanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/cetak', [AduanController::class, 'cetakLaporan'])->name('laporan.cetak');
    });

    // Shared Aduan Details & Tanggapan (Akses diatur di Controller)
    Route::get('/aduan/{id}', [AduanController::class, 'show'])->name('aduan.show');
    Route::post('/aduan/{id}/tanggapan', [AduanController::class, 'storeTanggapan'])->name('aduan.tanggapan');
});
