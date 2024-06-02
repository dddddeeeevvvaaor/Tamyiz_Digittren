@extends('layout')
@section('breadcrumb')
Tambah Bank
@endsection
@section('judul')
Tambah Bank
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
            <h5 class="mb-0">Tambah Bank</h5>
            <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('bank.store') }}" id="bankForm">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama Bank:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="no_rekening">Nomor Rekening:</label>
                        <input type="number" class="form-control" id="no_rekening" name="no_rekening" required>
                    </div>
                    <div class="form-group">
                        <label for="atas_nama">Atas Nama:</label>
                        <input type="text" class="form-control" id="atas_nama" name="atas_nama" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon:</label>
                        <input type="number" class="form-control" id="phone" name="phone" required>
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