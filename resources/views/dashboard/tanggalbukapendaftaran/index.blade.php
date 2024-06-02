@extends('layout')
@section('breadcrumb')
Tanggal Buka Pendaftaran
@endsection
@section('judul')
Tanggal Buka Pendaftaran
@endsection
@section('content')
@include('sweetalert::alert')

<!-- Menggunakan container-fluid untuk full width dan tambahan margin atas -->
<div class="container-fluid" style="background-color: #F7F6F6; padding: 15px; border-radius: 15px;">

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

    <!-- Card untuk konten utama -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tanggal Buka Pendaftaran</h5>
            @if(count($tanggalbukapendaftaran) > 0)
            @else
            <a href="{{ route('tanggalbukapendaftaran.create') }}" class="btn btn-primary">Tambah</a>
            @endif
        </div>
        <!-- Form untuk search -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Tanggal Buka</th>
                            <th scope="col">Tanggal Tutup</th>
                            <th scope="col">Tanggal Program</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tanggalbukapendaftaran as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal_buka }}</td>
                            <td>{{ $item->tanggal_tutup }}</td>
                            <td>{{ $item->tanggal_program }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('tanggalbukapendaftaran.edit', ['tanggalbukapendaftaran' => $item->id_tglpendaftaran]) }}" class="btn btn-sm btn-primary mx-1">Edit</a>
                                    <form action="{{ route('tanggalbukapendaftaran.destroy', ['tanggalbukapendaftaran' => $item->id_tglpendaftaran]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('cari').addEventListener('keyup', function() {
        var value = this.value.toLowerCase();
        var rows = document.querySelectorAll('table tbody tr');

        rows.forEach(function(row) {
            if (row.textContent.toLowerCase().includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@else
@endif
</div>

@endsection