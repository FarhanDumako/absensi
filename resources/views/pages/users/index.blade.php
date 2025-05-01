@extends('layouts.app')

@section('title', 'Users')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Management Users</h1>
               
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('user.index') }}">Users</a></div>
                    <div class="breadcrumb-item">All Users</div>
                </div>
            </div>
            
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                
                <h2 class="section-title">Users</h2>
                <p class="section-lead">Manage all users, such as editing, deleting, and more.</p>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>All Users</h4>
                                <div class="card-header-action">
                                    <form method="GET" action="{{ route('user.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="name" 
                                                value="{{ request('name') }}"> 
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr @if ($user->role == 'admin') class="table-success" @endif>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($user->role == 'admin') badge-success 
                                                            @elseif($user->role == 'karyawan') badge-primary 
                                                            @else badge-info 
                                                            @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('user.edit', $user->id) }}" 
                                                                class="btn btn-sm btn-info">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>

                                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" 
                                                                class="ml-2 delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="float-right">
                                    {{ $users->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Konfirmasi sebelum hapus -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
