@extends('layout')

@section('breadcrumb')
Akuntansi
@endsection

@section('judul')
Akuntansi
@endsection

@section('content')
@include('sweetalert::alert')

<style>
    .pagination a.page-link {
        color: #fff;
        /* White text */
        background-color: #007bff;
        /* Bootstrap primary color */
        border: 1px solid #007bff;
    }

    .pagination a.page-link:hover {
        background-color: #0056b3;
        /* A darker shade of blue for hover state */
    }

    .pagination li.disabled span.page-link {
        color: #6c757d;
        /* Disabled text color */
        background-color: #fff;
        /* White background */
        border: 1px solid #dee2e6;
        /* Light grey border */
    }

    .pagination .page-item.active .page-link {
        z-index: 1;
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    /* Add more styles here to match your site's theme */
</style>

<!-- Menggunakan container-fluid untuk full width dan tambahan margin atas -->
<div class="container-fluid" style="background-color: #F7F6F6; padding: 15px; border-radius: 15px;">

    @if(Auth::user()->role == 'admin')

    <!-- Alert untuk status dan error -->
    @if(session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <!-- Card untuk konten utama -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Akuntansi</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal" data-target="#modal-import">
                    <i class="fas fa-upload"></i> Import
                </button>
                <a href="{{ route('akuntansi_export') }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i> Export
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
                                <div class="callout callout-info">
                                    <h5>Download format import</h5>
                                    <p>Silahkan download file format import melalui tombol dibawah ini.</p>
                                    <a href="{{ route('akuntansi_format_import') }}" class="btn btn-primary text-white" style="text-decoration:none"><i class="fas fa-file-download"></i> Download</a>
                                </div>
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

        <!-- Form untuk filter tahun dan bulan -->
        <div class="card-body">
            <div class="form-group row">
                <label for="tahun" class="col-sm-2 col-form-label">Tahun:</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" id="tahun" placeholder="Masukkan tahun">
                </div>
                <label for="bulan" class="col-sm-2 col-form-label">Bulan:</label>
                <div class="col-sm-4">
                    <select class="form-control" id="bulan">
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">Nopember</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
            </div>

            <!-- Tabel responsif untuk data akuntansi -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Keterangan Program</th>
                            <th>Keterangan Infaq</th>
                            <th>Debet</th>
                            <th>Saldo Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $saldoTotal = 0; @endphp
                        @foreach($akuntansi as $akun)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $akun->tanggal }}</td>
                            <td>{{ $akun->keterangan_program }}</td>
                            <td>{{ $akun->keterangan_infaq }}</td>
                            <td>{{ 'Rp ' . number_format($akun->debet, 0, ',', '.') }}</td>
                            @php $saldoTotal += $akun->debet; @endphp
                            <td>{{ 'Rp ' . number_format($saldoTotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5"><strong>Total</strong></td>
                            <!-- jika data kosong, maka saldo total diisi 0 -->
                            <td><strong>{{ 'Rp ' . number_format($akuntansi->sum('saldo'), 0, ',', '.') }}</strong></td>
                        </tr>
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
                    @if ($akuntansi->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($akuntansi->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $akuntansi->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                            @endif

                            {{-- Pagination Number --}}
                            <li class="page-item">
                                <div class="page-link">{{ $akuntansi->currentPage() }} dari {{ $akuntansi->lastPage() }}</div>
                            </li>

                            {{-- Next Page Link --}}
                            @if ($akuntansi->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $akuntansi->nextPageUrl() }}" rel="next">Next</a>
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

    <!-- Javascript untuk handle filter dan update tabel -->
    <script>
        function changePerPage() {
            var perPage = document.getElementById('jumlah').value;
            window.location.href = `{{ url('/dashboard/laporanakuntansi/akuntansi?perPage=${perPage}') }}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tahunInput = document.getElementById('tahun');
            const bulanInput = document.getElementById('bulan');

            tahunInput.addEventListener('change', fetchData);
            bulanInput.addEventListener('change', fetchData);

            async function fetchData() {
                try {
                    const tahun = tahunInput.value;
                    const bulan = bulanInput.value;
                    const response = await fetch(`/dashboard/laporanakuntansi/akuntansi/filter?tahun=${tahun}&bulan=${bulan}`);
                    const data = await response.json();
                    updateTable(data);
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            function updateTable(data) {
                const tableBody = document.querySelector('.table tbody');
                tableBody.innerHTML = '';

                data.forEach((item, index) => {
                    const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.tanggal}</td>
                    <td>${item.keterangan_program}</td>
                    <td>${item.keterangan_infaq}</td>
                    <td>${item.debet}</td>
                    <td>${item.saldo}</td>
                </tr>
            `;
                    tableBody.innerHTML += row;
                });
            }
        });
    </script>
    @endif
</div>
@endsection