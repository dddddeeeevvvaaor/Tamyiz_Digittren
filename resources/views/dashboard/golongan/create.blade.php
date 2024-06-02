@extends('layout')
@section('breadcrumb')
Tambah Golongan
@endsection
@section('judul')
Tambah Golongan
@endsection
@section('content')
@include('sweetalert::alert')

<div style="">
    @if(Auth::user()->role == 'admin')
    <!-- tambahkan kondisi untuk menampilkan pesan -->
    @if(session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <!-- tambahkan kondisi untuk menampilkan pesan error -->
    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Golongan</h5>
            <a href="{{ route('golongan.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('golongan.store') }}" id="golonganForm">
                    @csrf
                    <div class="form-group">
                        <label for="nama_golongan">Nama Golongan:</label>
                        <input type="text" class="form-control" id="nama_golongan" name="nama_golongan" required>
                    </div>
                    <div class="form-group">
                        <label for="max_santri">Maksimal Santri:</label>
                        <input type="number" class="form-control" id="max_santri" name="max_santri" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    @else
    @endif
</div>
@endsection