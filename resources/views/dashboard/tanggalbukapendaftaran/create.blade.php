@extends('layout')
@section('breadcrumb')
Tambah Tanggal Buka Pendaftaran
@endsection
@section('judul')
Tambah Tanggal Buka Pendaftaran
@endsection
@section('content')
@include('sweetalert::alert')

<div>

    <div class="card">
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
        <div class="card-header">
            <h5>Tambah Tanggal Buka Pendaftaran</h5>
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('tanggalbukapendaftaran.store') }}" id="tanggalbukapendaftaranForm">

                @csrf

                <div class="form-group">
                    <label for="tanggal_buka">Tanggal Buka:</label>
                    <input type="date" name="tanggal_buka" class="form-control" id="tanggal_buka">
                </div>

                <div class="form-group">
                    <label for="tanggal_tutup">Tanggal Tutup:</label>
                    <input type="date" name="tanggal_tutup" class="form-control" id="tanggal_tutup">
                </div>

                <div class="form-group">
                    <label for="tanggal_program">Tanggal Program:</label>
                    <input type="date" name="tanggal_program" class="form-control" id="tanggal_program">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>

            </form>

        </div>
    </div>

</div>
@endsection