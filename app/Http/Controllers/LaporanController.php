<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::query();

        // Filter berdasarkan nama atau email
        if ($request->has('name') && $request->name) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%')
                  ->orWhere('email', 'like', '%' . $request->name . '%');
            });
        }
        
        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal_pengajuan', substr($request->bulan, 5, 2))
                  ->whereYear('tanggal_pengajuan', substr($request->bulan, 0, 4));
        }
        
        // Filter berdasarkan jenis pengajuan
        if ($request->has('jenis_pengajuan') && $request->jenis_pengajuan) {
            $query->where('jenis_pengajuan', $request->jenis_pengajuan);
        }
    
        // Filter berdasarkan tanggal pengajuan
        if ($request->has('tanggal_pengajuan') && $request->tanggal_pengajuan) {
            $query->whereDate('tanggal_pengajuan', $request->tanggal_pengajuan);
        }
    
        // Filter berdasarkan status pengajuan
        if ($request->has('status_pengajuan') && $request->status_pengajuan) {
            $query->where('status_pengajuan', $request->status_pengajuan);
        }
    
        // Ambil data dengan paginasi
        $pengajuans = $query->paginate(10);
    
        return view('pages.pengajuans.laporanpengajuan', compact('pengajuans'));
    }
    

    public function export(Request $request)
    {
        return Excel::download(
            new LaporanExport($request->all()),
            'laporan_pengajuan.xlsx'
        );
    }
}
