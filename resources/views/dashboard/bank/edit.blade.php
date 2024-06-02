@extends('layout')
@section('breadcrumb')
Edit Bank
@endsection
@section('judul')
Edit Bank
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
            <h5 class="mb-0">Edit Bank</h5>
            <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('bank.update', ['bank' => $bank->id_bank]) }}" id="bankForm">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <div class="form-group">
                        <label for="nama">Nama Bank:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required value="{{ $bank->nama }}">
                    </div>
                    <div class="form-group">
                        <label for="no_rekening">Nomor Rekening:</label>
                        <input type="text" class="form-control" id="no_rekening" name="no_rekening" required value="{{ $bank->no_rekening }}">
                    </div>
                    <div class="form-group">
                        <label for="atas_nama">Atas Nama:</label>
                        <input type="text" class="form-control" id="atas_nama" name="atas_nama" required value="{{ $bank->atas_nama }}">
                    </div>
                    <div class="form-group
                        <label for="phone">Nomor Telepon:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required value="{{ $bank->phone }}">
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