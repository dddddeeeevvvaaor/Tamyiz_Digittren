@extends('layout')
@section('breadcrumb')
Pengumuman
@endsection
@section('judul')
Pengumuman
@endsection
@section('content')
@include('sweetalert::alert')

<div class="container-fluid">
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
            <h5 class="mb-0">Pengumuman</h5>
            <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('pengumuman.store') }}" id="pengumumanForm">
                    @csrf
                    @if(count($pengumuman) > 0)
                    @foreach($pengumuman as $p)
                    <div class="form-group">
                        <label for="judul">Judul:</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ $p->judul }}" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi:</label>
                        <textarea class="form-control" id="isi" name="isi" rows="3" required>{{ $p->isi }}</textarea>
                    </div>
                    @endforeach
                    @else
                    <div class="form-group">
                        <label for="judul">Judul:</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi:</label>
                        <textarea class="form-control" id="isi" name="isi" rows="3" required></textarea>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Notifikasi WhatsApp</button>
                </form>
            </div>
        </div>
    </div>
    @else
    <!-- Opsi jika user bukan admin -->
    <p>Anda tidak memiliki akses untuk melihat halaman ini.</p>
    @endif
</div>

@endsection