@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Absensi</h1>
        </div>

        <div class="section-body">
            <form method="GET" action="{{ route('laporan.absensi.index') }}" class="row mb-3">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Cari Nama" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-3">
                    <select name="status_verifikasi_QRcode" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status_verifikasi_QRcode') == '1' ? 'selected' : '' }}>Valid</option>
                        <option value="0" {{ request('status_verifikasi_QRcode') == '0' ? 'selected' : '' }}>Invalid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="mb-3 text-right">
                <a href="{{ route('laporan.absensi.export', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal & Waktu</th>
                            <th>Lokasi</th>
                            <th>Status Verifikasi QRCode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $absen)
                        <tr>
                            <td>{{ $absen->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal_dan_waktu)->format('d M Y H:i') }}</td>
                            <td>{{ $absen->lokasi }}</td>
                            <td>
                                <span class="badge 
                                    @if($absen->status_verifikasi_QRcode == 'valid') badge-success
                                    @elseif($absen->status_verifikasi_QRcode == 'invalid') badge-danger
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($absen->status_verifikasi_QRcode) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $absensis->withQueryString()->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
