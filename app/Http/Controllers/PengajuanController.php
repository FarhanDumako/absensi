<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\User;

class PengajuanController extends Controller
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

    $pengajuans = $query->paginate(10); // Atur pagination sesuai kebutuhan

    return view('pages.pengajuans.index', compact('pengajuans'));
}


    public function create()
    {
        $users = User::all();
        return view('pages.pengajuans.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pengajuan' => 'required|date',
            'jenis_pengajuan' => 'required|in:izin,sakit',
            'dokumen_pendukung' => 'nullable|string',
            'status_pengajuan' => 'nullable|in:pending,approved,rejected',
        ]);

        Pengajuan::create($request->all());

        return redirect()->route('pengajuans.index')->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('user')->findOrFail($id);
        return view('pages.pengajuans.show', compact('pengajuan'));
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $users = User::all();
        return view('pages.pengajuans.edit', compact('pengajuan', 'users'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pengajuan' => 'required|date',
            'jenis_pengajuan' => 'required|in:izin,sakit',
            'dokumen_pendukung' => 'nullable|string',
            'status_pengajuan' => 'nullable|in:pending,approved,rejected',
        ]);

        $pengajuan->update($request->all());

        return redirect()->route('pengajuans.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->delete();

        return redirect()->route('pengajuans.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Pengajuan $pengajuan)
{
    $request->validate([
        'status_pengajuan' => 'required|in:pending,approved,rejected',
    ]);

    $pengajuan->update([
        'status_pengajuan' => $request->status_pengajuan,
    ]);

    return redirect()->route('pengajuans.index')->with('success', 'Status pengajuan berhasil diperbarui.');
}

}
