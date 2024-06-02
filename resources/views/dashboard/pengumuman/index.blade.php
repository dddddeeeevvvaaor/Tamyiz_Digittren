@extends('layout')
@section('breadcrumb')
Pengumuman
@endsection
@section('judul')
Pengumuman
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

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pengumuman</h5>
            <!-- Tambah button -->
            <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">Tambah</a>
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
                            <th>No</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach($pengumuman as $p)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$p->judul}}</td>
                            <td>{{$p->isi}}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <!-- <a href="{{ route('pengumuman.edit', ['pengumuman' => $p->id_pengumuman]) }}" class="btn btn-sm btn-primary mx-1">Edit</a> -->
                                    <form action="{{ route('pengumuman.destroy', ['pengumuman' => $p->id_pengumuman]) }}" method="POST">
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