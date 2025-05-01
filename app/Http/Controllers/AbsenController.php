<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\User;

class AbsenController extends Controller
{
    public function index(Request $request)
{
    $absens = Absen::with('user')
        ->when($request->input('name'), function ($query, $name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%')
                  ->orWhere('email', 'like', '%' . $name . '%');
            });
        })
        ->when($request->input('tanggal'), function ($query, $tanggal) {
            $query->whereDate('tanggal_dan_waktu', $tanggal);
        })
        ->orderBy('tanggal_dan_waktu', 'desc')
        ->paginate(5);

    return view('pages.absens.index', compact('absens'));
}

    public function create()
    {
        $users = User::all();
        return view('pages.absens.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_dan_waktu' => 'required|date',
            'lokasi' => 'required|string',
            'status_verifikasi_QRcode' => 'required|in:verified,pending',
        ]);

        Absen::create($request->all());

        return redirect()->route('absens.index')->with('success', 'Absen berhasil dibuat.');
    }

    public function show($id)
    {
        $absen = Absen::with('user')->findOrFail($id);
        return view('pages.absens.show', compact('absen'));
    }

    public function edit($id)
    {
        $absen = Absen::findOrFail($id);
        $users = User::all();
        return view('pages.absens.edit', compact('absen', 'users'));
    }

    public function update(Request $request, $id)
    {
        $absen = Absen::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_dan_waktu' => 'required|date',
            'lokasi' => 'required|string',
            'status_verifikasi_QRcode' => 'required|in:verified,pending',
        ]);

        $absen->update($request->all());

        return redirect()->route('absens.index')->with('success', 'Absen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $absen = Absen::findOrFail($id);
        $absen->delete();

        return redirect()->route('absens.index')->with('success', 'Absen berhasil dihapus.');
    }
}
