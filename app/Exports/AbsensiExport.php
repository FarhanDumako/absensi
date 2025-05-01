<?php

namespace App\Exports;

use App\Models\Absen;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Absen::with('user');

        // Filter berdasarkan tanggal
        if ($this->request->has('tanggal') && $this->request->tanggal) {
            $query->whereDate('tanggal_dan_waktu', $this->request->tanggal);
        }

        // Filter berdasarkan status QR code
        if ($this->request->has('status_verifikasi_QRcode') && $this->request->status_verifikasi_QRcode) {
            $query->where('status_verifikasi_QRcode', $this->request->status_verifikasi_QRcode);
        }

        // Filter nama user
        if ($this->request->has('name') && $this->request->name) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->request->name . '%');
            });
        }

        return $query->get()->map(function ($absen) {
            return [
                'Nama' => $absen->user->name ?? '-',
                'Tanggal & Waktu' => Carbon::parse($absen->tanggal_dan_waktu)->format('d M Y H:i'),
                'Lokasi' => $absen->lokasi ?? '-',
                'Status Verifikasi QRCode' => ucfirst($absen->status_verifikasi_QRcode),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Tanggal & Waktu', 'Lokasi', 'Status Verifikasi QRCode'];
    }
}
