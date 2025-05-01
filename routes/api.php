<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\PengajuanController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 🔐 Public routes
Route::post('/login', [AuthController::class, 'login']);

// 📄 Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // 👤 Pelanggan
    // Route::get('/check-nik', [PelangganController::class, 'checkNik']);
    // Menampilkan daftar pengajuan milik user yang login
    Route::get('/pengajuan', [PengajuanController::class, 'index']);
    Route::post('/pengajuan', [PengajuanController::class, 'store']);
    Route::post('/absen', [AbsenController::class, 'store']);

});


