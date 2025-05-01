<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absen;
use App\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        
        $totalUser = User::count();
        $totalPengajuan = Pengajuan::count();
        $totalAbsen = Absen::count();
    
        $totalIzin = Pengajuan::where('jenis_pengajuan', 'izin')->count();
        $totalSakit = Pengajuan::where('jenis_pengajuan', 'sakit')->count();
    
        return view('pages.dashboard', compact(
            'totalUser',
            'totalPengajuan',
            'totalAbsen',
            'totalIzin',
            'totalSakit'
        ));
    }
}
