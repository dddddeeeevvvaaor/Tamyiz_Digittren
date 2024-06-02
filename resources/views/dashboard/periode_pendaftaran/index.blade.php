@extends('layout')
@section('breadcrumb')
Periode Pendaftaran
@endsection
@section('judul')
Periode Pendaftaran
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
            <h5 class="mb-0">Periode Pendaftaran</h5>
            <!-- Tambah button -->
            <a href="{{ route('periode_pendaftaran.create') }}" class="btn btn-primary">Tambah</a>
        </div>

        <!-- Form untuk search -->
        <div class="card-body">
            <div class="form-group row">
                <label for="search" class="col-sm-1 col-form-label">Search:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="cari" name="cari" placeholder="Cari...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Periode</th>
                            <th scope="col">Diskon</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periode as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                @php
                                if ($item->periode % 12 == 0) {
                                echo ($item->periode / 12) . ' tahun';
                                } else {
                                echo $item->periode . ' bulan';
                                }
                                @endphp
                            </td>
                            <td>{{ $item->diskon }} %</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('periode_pendaftaran.edit', ['periode' => $item->id_periodedaftar]) }}" class="btn btn-sm btn-primary mx-1">Edit</a>
                                    <form action="{{ route('periode_pendaftaran.destroy', ['periode' => $item->id_periodedaftar]) }}" method="POST">
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