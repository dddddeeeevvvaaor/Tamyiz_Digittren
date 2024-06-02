@extends('layout')
@section('breadcrumb')
Data Calon Santri
@endsection
@section('judul')
Data Calon Santri
@endsection
@section('content')
@include('sweetalert::alert')

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
            <h5 class="mb-0">Data Calon Santri</h5>
            <!-- Card tools untuk aksi seperti tambah dan export -->
            <div class="card-tools">
                <a href="{{ route('create_calonsiswa') }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-plus"></i> Tambah
                    <a href="{{ route('calonsiswa_export') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                </a>
            </div>
            <!-- Modal import  -->
            <div class="modal fade" id="modal-import">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Data Akuntansi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form name="contact-form" action="{{ route('akuntansi_import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group row pt-2">
                                    <label for="file_import" class="col-sm-2 col-form-label">File Import</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file_import" id="customFile" accept="application/vnd.ms-excel">
                                            <label class="custom-file-label" for="customFile">Pilih file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal import -->
        </div>

        <!-- Form untuk search -->
        <div class="card-body">
            <div class="form-group row">
                <label for="search" class="col-sm-1 col-form-label" style="padding-top: 5px;">Search:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="cari" name="cari" placeholder="Cari...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>NIST</th>
                            <th>Nama</th>
                            <th>Nomor WhatsApp</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['santri']['nist'] }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td>{{ $item['phone'] }}</td>
                            <td style="vertical-align: middle;">
                                @if(isset($pembayaran[$index]))
                                @if($pembayaran[$index]['status'] == 0)
                                <p style="color: red; margin: 0;">Belum Bayar</p>
                                @elseif($pembayaran[$index]['status'] == 1)
                                <p style="color: green; margin: 0;">Diterima</p>
                                @else
                                <p style="color: red; margin: 0;">Ditolak</p>
                                @endif
                                @else
                                <p style="margin: 0;">Belum Ada Data Pembayaran</p>
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                <a href="{{ route('updatepassworddatasiswa', $item['id_user']) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit Password
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-size">
                        <select class="form-select form-select-sm" id="jumlah" onchange="changePerPage()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <!-- Tempatkan komponen lain untuk navigasi pagination di sini jika diperlukan -->
                </div>
            </div>
        </div>
    </div>
    <script>
        function changePerPage() {
            var perPage = document.getElementById('jumlah').value;
            window.location.href = `{{ url('/dashboard/data_siswa/calonsiswa?perPage=${perPage}') }}`;
        }

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
    <!-- Bagian untuk pengguna selain admin -->
    @endif
</div>
@endsection