<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{

    public function index(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Query pengajuan berdasarkan user_id yang sedang login
        $pengajuan = Pengajuan::where('user_id', $user->id)->get();

        // Mengembalikan response JSON
        return response()->json([
            'status' => true,
            'message' => 'Data pengajuan berhasil diambil',
            'data' => $pengajuan
        ]);
    }

    /**
     * Menyimpan pengajuan baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'jenis_pengajuan' => 'required|string|in:izin,sakit', // Pastikan hanya izin atau sakit
            'dokumen_pendukung' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048', // Sesuaikan tipe file
        ]);

        // Menyimpan file dokumen pendukung jika ada
       
        if ($request->hasFile('dokumen_pendukung')) {
            // Menyimpan file ke storage
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');
        } else {
            return response()->json([
                'message' => 'Dokumen pendukung wajib diunggah.',
            ], 400);
        }

        // Membuat pengajuan baru
        $pengajuan = Pengajuan::create([
            'user_id' => $request->user()->id, // Mengambil ID user yang sedang login
            'tanggal_pengajuan' => now(),
            'jenis_pengajuan' => $validatedData['jenis_pengajuan'],
            'dokumen_pendukung' => $dokumenPath, // Menyimpan path dokumen
            'status_pengajuan' => 'pending', // Status default pengajuan
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dibuat',
            'pengajuan' => $pengajuan,
        ], 201);
    }
}
