@extends('layout')
@section('breadcrumb')
Golongan
@endsection
@section('judul')
Golongan
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
            <h5 class="mb-0">Data Golongan</h5>
            <!-- Tambah button -->
            <a href="{{ route('golongan.create') }}" class="btn btn-primary">Tambah</a>
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
                            <th scope="col">Nama Golongan</th>
                            <th scope="col">Maksimal Santri</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($golongan as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->nama_golongan}}</td>
                            <td>{{$item->max_santri}}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('golongan.edit', ['golongan' => $item->id_golongan]) }}" class="btn btn-sm btn-primary mx-1">Edit</a>
                                    <form action="{{ route('golongan.destroy', ['golongan' => $item->id_golongan]) }}" method="POST">
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