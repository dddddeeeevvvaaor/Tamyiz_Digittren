@extends('layout')
@section('breadcrumb')
Pembayaran Ditolak
@endsection
@section('judul')
Pembayaran Ditolak
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
            <h5 class="mb-0">Pembayaran Ditolak</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
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
                            <th>Nama Lengkap</th>
                            <th>Bukti Pembayaran</th>
                            <th>Detail Pendaftaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        @if($student['status'] == 2) <!-- Menampilkan hanya status pembayaran yang ditolak -->
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach($user as $item3)
                            @endforeach
                            <td>{{ $student->siswa->user->nama }}</td>
                            <td>
                                <a href="{{ route('bukti', $student->nist) }}">Lihat</a>
                            </td>
                            <td>
                                <a href="{{ route('detail.pendaftaran', $student->nist) }}">Detail</a>
                            </td>
                            <td style="vertical-align: middle;">
                                <p style="color: red; margin: 0;">Ditolak</p>
                            </td>
                        </tr>
                        @endif
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
                    {{-- Pagination Links --}}
                    @if ($students->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($students->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $students->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                            @endif

                            {{-- Pagination Number --}}
                            <li class="page-item">
                                <div class="page-link">{{ $students->currentPage() }} dari {{ $students->lastPage() }}</div>
                            </li>

                            {{-- Next Page Link --}}
                            @if ($students->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $students->nextPageUrl() }}" rel="next">Next</a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changePerPage() {
            var perPage = document.getElementById('jumlah').value;
            window.location.href = `{{ url('/dashboard/pembayaran/pembayaran_ditolak?perPage=${perPage}') }}`;
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

    <!-- Card untuk konten utama -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pembayaran Infaq Ditolak</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Bukti Pembayaran</th>
                            <th>Detail Pendaftaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infaq as $infaq_perbulan)
                        @if($infaq_perbulan['status'] == 2) <!-- Menampilkan hanya status pembayaran yang diterima -->
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach($user as $item3)
                            @endforeach
                            <td>{{ $infaq_perbulan->siswa->user->nama }}</td>
                            <td>
                                <a href="{{ route('bukti_infaq', $infaq_perbulan->id_infaq) }}">Lihat</a>
                            </td>
                            <td>
                                <a href="{{ route('detail.pendaftaran', $infaq_perbulan->id_infaq) }}">Detail</a>
                            </td>
                            <td style="vertical-align: middle;">
                                <p style="color: red; margin: 0;">Ditolak</p>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-size">
                        <select class="form-select form-select-sm" id="jumlahInfaq" onchange="changePerPageInfaq()">
                            <option value="5" {{ $perPageInfaq == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPageInfaq == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPageInfaq == 20 ? 'selected' : '' }}>20</option>
                            <option value="all" {{ $perPageInfaq == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <!-- Tempatkan komponen lain untuk navigasi pagination di sini jika diperlukan -->
                    {{-- Pagination Links --}}
                    @if ($infaq->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($infaq->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $infaq->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                            @endif

                            {{-- Pagination Number --}}
                            <li class="page-item">
                                <div class="page-link">{{ $infaq->currentPage() }} dari {{ $infaq->lastPage() }}</div>
                            </li>

                            {{-- Next Page Link --}}
                            @if ($infaq->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $infaq->nextPageUrl() }}" rel="next">Next</a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changePerPageInfaq() {
            var perPageInfaq = document.getElementById('jumlahInfaq').value;
            window.location.href = `{{ url('/dashboard/pembayaran/pembayaran_ditolak?perPageInfaq=${perPageInfaq}') }}`;
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
    @if (!isset($item))
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
        </div>
    </div>
    @endif
    <!-- Bagian untuk pengguna selain admin -->
    @endif
</div>

@endsection