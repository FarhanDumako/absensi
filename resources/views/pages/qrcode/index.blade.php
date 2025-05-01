@extends('layouts.app')

@section('title', 'Daftar QR Code')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar QR Code</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                <div class="breadcrumb-item">QR Code</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Manajemen QR Code</h2>
            <p class="section-lead">Berikut adalah daftar QR Code yang telah dibuat.</p>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                    <form action="{{ route('generate.qrcode') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" required>
                        </div>
                    
                        
                    
                        <button type="submit" class="btn btn-primary">Buat QR Code</button>
                    </form>
                    
                    <!-- Menampilkan pesan jika QR Code belum ada -->
                    @if(isset($message))
    <div class="alert alert-warning">
        {{ $message }}
    </div>
@else
    @if(empty($qrCodes))
        <div class="alert alert-info">
            Belum ada QR Code yang dibuat.
        </div>
    @else
        <div class="row">
            @foreach($qrCodes as $qrCode)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">QR Code {{ basename($qrCode) }}</h5>
                            <div class="qr-image">
                                <img src="{{ asset('qrcodes/' . basename($qrCode)) }}" alt="QR Code" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
