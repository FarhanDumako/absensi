<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absen::with('user');

        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('tanggal_dan_waktu', $request->tanggal);
        }

        if ($request->has('status_verifikasi_QRcode') && $request->status_verifikasi_QRcode) {
            $query->where('status_verifikasi_QRcode', $request->status_verifikasi_QRcode);
        }

        if ($request->has('name') && $request->name) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $absensis = $query->paginate(10);

        return view('pages.absens.laporan', compact('absensis'));
    }

    public function export(Request $request)
    {
        return Excel::download(new AbsensiExport($request), 'laporan_absensi.xlsx');
    }
}
