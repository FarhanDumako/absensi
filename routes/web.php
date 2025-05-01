<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanAbsensiController;
use App\Http\Controllers\QrCodeController; 
use Illuminate\Support\Facades\Route;

// Route untuk halaman login
Route::get('/', function () {
    return view('pages.auth.auth-login');
});

// Grup route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Dashboard utama setelah login
    Route::get('home', [DashboardController::class, 'index'])->name('home');

    // Dashboard
    Route::resource('/dashboard', DashboardController::class);
    // Route generate QR code
    Route::get('qrcode', [QRCodeController::class, 'index'])->name('qrcode');
      Route::post('/generate-qrcode', [QRCodeController::class, 'generateQrCode'])->name('generate.qrcode');

    // Grup route khusus untuk admin
    Route::middleware(['role:admin'])->group(function () {
        
        // Manajemen user
        Route::resource('/user', UserController::class);
        Route::get('user/profil/{id}', [UserController::class, 'profil'])->name('user.profil');
        Route::put('user/updateProfile/{id}', [UserController::class, 'updateProfile'])->name('user.updateProfile');
    

        // Manajemen absensi
        Route::resource('/absens', AbsenController::class);

        // Manajemen pengajuan izin/sakit
        Route::resource('/pengajuans', PengajuanController::class);
        Route::patch('/pengajuans/{pengajuan}/status', [PengajuanController::class, 'updateStatus'])->name('pengajuans.updateStatus');

        //laporanpengajuan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        //laporan absen
        Route::get('/laporan/absensi', [LaporanAbsensiController::class, 'index'])->name('laporan.absensi.index');
        Route::get('/laporan/absensi/export', [LaporanAbsensiController::class, 'export'])->name('laporan.absensi.export');

       
    });
});
