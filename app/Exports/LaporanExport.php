<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pengajuan::with('user');

        if (!empty($this->filters['name'])) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->filters['name'] . '%')
                  ->orWhere('email', 'like', '%' . $this->filters['name'] . '%');
            });
        }

        if (!empty($this->filters['tanggal_pengajuan'])) {
            $query->whereDate('tanggal_pengajuan', $this->filters['tanggal_pengajuan']);
        }

        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('tanggal_pengajuan', substr($this->filters['bulan'], 5, 2))
                  ->whereYear('tanggal_pengajuan', substr($this->filters['bulan'], 0, 4));
        }

        if (!empty($this->filters['jenis_pengajuan'])) {
            $query->where('jenis_pengajuan', $this->filters['jenis_pengajuan']);
        }

        if (!empty($this->filters['status_pengajuan'])) {
            $query->where('status_pengajuan', $this->filters['status_pengajuan']);
        }

        return $query->get()->map(function ($pengajuan) {
            return [
                'Nama' => $pengajuan->user->name ?? '-',
                'Email' => $pengajuan->user->email ?? '-',
                'Tanggal Pengajuan' => Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y'),
                'Jenis Pengajuan' => ucfirst($pengajuan->jenis_pengajuan),
                'Dokumen Pendukung' => $pengajuan->dokumen_pendukung ? 'Ada' : 'Tidak Ada',
                'Status' => ucfirst($pengajuan->status_pengajuan),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Tanggal Pengajuan',
            'Jenis Pengajuan',
            'Dokumen Pendukung',
            'Status',
        ];
    }
}
