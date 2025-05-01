@extends('layouts.app')

@section('title', 'Absensi')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Absensi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Absensi</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Manajemen Absensi</h2>
            <p class="section-lead">Berikut adalah daftar absensi pengguna.</p>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')

                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Absensi</h4>
                            <div class="card-header-action">
                                <form method="GET" action="{{ route('absens.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari Nama / Email" name="name" value="{{ request('name') }}">
                                        <input type="date" class="form-control ml-2" name="tanggal" value="{{ request('tanggal') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Lokasi</th>
                                            <th>Status Verifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($absens as $absen)
                                            <tr>
                                                <td>{{ $absen->user->name ?? '-' }}</td>
                                                <td>{{ $absen->user->email ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($absen->tanggal_dan_waktu)->format('d M Y H:i') }}</td>
                                                <td>{{ $absen->lokasi }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if($absen->status_verifikasi_QRcode == 'valid') badge-success
                                                        @elseif($absen->status_verifikasi_QRcode == 'tidak valid') badge-danger
                                                        @else badge-secondary
                                                        @endif">
                                                        {{ ucfirst($absen->status_verifikasi_QRcode) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data absensi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-right">
                                {{ $absens->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
