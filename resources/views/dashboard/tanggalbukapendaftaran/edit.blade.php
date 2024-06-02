@extends('layout')
@section('breadcrumb')
Edit Tanggal Buka Pendaftaran
@endsection
@section('judul')
Edit Tanggal Buka Pendaftaran
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
            <h5 class="mb-0">Edit Tanggal Buka Pendaftaran</h5>
            <a href="{{ route('tanggalbukapendaftaran.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <!-- Your form code in edit.blade.php -->
                <form method="POST" action="{{ route('tanggalbukapendaftaran.update', ['tanggalbukapendaftaran' => $tanggalbukapendaftaran->id_tglpendaftaran]) }}" id="tanggalbukapendaftaranForm">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <div class="form-group">
                        <label for="tanggal_buka">Tanggal Buka:</label>
                        <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" required value="{{ $tanggalbukapendaftaran->tanggal_buka }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_tutup">Tanggal Tutup:</label>
                        <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" required value="{{ $tanggalbukapendaftaran->tanggal_tutup }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_program">Tanggal Program:</label>
                        <input type="date" class="form-control" id="tanggal_program" name="tanggal_program" required value="{{ $tanggalbukapendaftaran->tanggal_program }}">
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