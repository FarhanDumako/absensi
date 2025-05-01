<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absen;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    /**
     * Menyimpan data absensi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'lokasi' => 'required|string|max:255',
            'status_verifikasi_QRcode' => 'required|boolean',
        ]);
    
        // Membuat absensi baru
        $absensi = Absen::create([
            'user_id' => $request->user()->id, // Mengambil ID user yang sedang login
            'tanggal_dan_waktu' => now(), // Waktu absen sekarang
            'lokasi' => $validatedData['lokasi'],
            'status_verifikasi_QRcode' => $validatedData['status_verifikasi_QRcode'],
        ]);
    
        return response()->json([
            'message' => 'Absensi berhasil disimpan',
            'absensi' => $absensi,
        ], 201);
    }
    
}
