@extends('layouts.app')

@section('title', 'Data Pengajuan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Pengajuan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Pengajuan</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Manajemen Pengajuan</h2>
            <p class="section-lead">Berikut adalah daftar pengajuan izin/sakit dari pengguna.</p>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')

                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Pengajuan</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('pengajuans.index') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Cari Nama / Email" name="name" value="{{ request('name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="jenis_pengajuan" class="custom-select">
                                                <option value="">Semua Jenis Pengajuan</option>
                                                <option value="izin" {{ request('jenis_pengajuan') == 'izin' ? 'selected' : '' }}>Izin</option>
                                                <option value="sakit" {{ request('jenis_pengajuan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="tanggal_pengajuan" value="{{ request('tanggal_pengajuan') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select name="status_pengajuan" class="custom-select">
                                                <option value="">Semua Status</option>
                                                <option value="pending" {{ request('status_pengajuan') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ request('status_pengajuan') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ request('status_pengajuan') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class=" align-items-end">
                                        <button class="btn btn-primary w-99" type="submit">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Dokumen Pendukung</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pengajuans as $pengajuan)
                                            <tr>
                                                <td>{{ $pengajuan->user->name ?? '-' }}</td>
                                                <td>{{ $pengajuan->user->email ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y') }}</td>
                                                <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
                                                <td>
                                                    @if ($pengajuan->dokumen_pendukung)
                                                        <a href="{{ asset('storage/' . $pengajuan->dokumen_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                            Lihat Dokumen
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Tidak ada</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('pengajuans.updateStatus', $pengajuan->id) }}" method="POST" class="d-flex align-items-center">
                                                        @csrf
                                                        @method('PATCH')
                                                  
                                                        {{-- Badge Status --}}
                                                        <span class="badge mr-2 
                                                            @if($pengajuan->status_pengajuan == 'approved') badge-success
                                                            @elseif($pengajuan->status_pengajuan == 'rejected') badge-danger
                                                            @else badge-warning
                                                            @endif">
                                                            {{ ucfirst($pengajuan->status_pengajuan) }}
                                                        </span>
                                                  
                                                        {{-- Dropdown + Button --}}
                                                        <div class="input-group input-group-sm" style="max-width: 250px;">
                                                            <select name="status_pengajuan" class="custom-select">
                                                                <option value="pending" {{ $pengajuan->status_pengajuan == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="approved" {{ $pengajuan->status_pengajuan == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                <option value="rejected" {{ $pengajuan->status_pengajuan == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button type="submit" class="btn btn-success" title="Ubah Status" onclick="return confirm('Yakin ingin mengubah status?')">
                                                                    <i class="fas fa-sync-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>                                                 
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data pengajuan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-right">
                                {{ $pengajuans->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
