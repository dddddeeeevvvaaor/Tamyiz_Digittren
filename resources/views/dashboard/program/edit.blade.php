@extends('layout')
@section('breadcrumb')
Program
@endsection
@section('judul')
Program
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
            <h5 class="mb-0">Edit Program</h5>
            <a href="{{ route('program.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('program.update', ['program' => $program->id_program]) }}" id="programForm">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required value="{{ $program->nama }}">
                    </div>
                    <div class="form-group">
                        <label for="nominal">Nominal:</label>
                        <input type="text" class="form-control" id="nominal" name="nominal" required value="Rp {{ number_format($program->nominal, 0, ',', '.') }}">
                        <small id="nominalHelp" class="form-text text-muted"></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    @else
    @endif
</div>

<script>
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        // Batas maksimal 12 digit (sampai 1 triliun)
        rupiah = rupiah.substr(0, 12);

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1].substr(0, 2) : rupiah;

        return 'Rp ' + rupiah;
    }

    document.getElementById('nominal').addEventListener('keyup', function(e) {
        var nominal = document.getElementById('nominal');
        nominal.value = formatRupiah(nominal.value);
    });
</script>

@endsection