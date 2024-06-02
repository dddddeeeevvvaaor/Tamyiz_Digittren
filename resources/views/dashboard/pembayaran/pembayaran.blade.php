@extends('layout')
@section('breadcrumb')
Pembayaran
@endsection
@section('judul')
Pembayaran
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
            <h5 class="mb-0">Pembayaran</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
                <a href="{{ route('create_pembayaran_admin') }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-plus"></i> Tambah
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
                            <th>Nama Lengkap</th>
                            <th>Bukti Pembayaran</th>
                            <th>Detail Pendaftaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        @if($student['status'] == 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach($user as $item3)
                            @endforeach
                            <td>{{ $student->siswa->user->nama }}</td>
                            <td>
                                <a href="{{ route('bukti', $student->nist) }}">Lihat Bukti Pembayaran</a>
                            </td>
                            <td>
                                <a href="{{ route('detail.pendaftaran', $student->nist) }}">Detail Pendaftaran</a>
                            </td>
                            <td style="vertical-align: middle;">
                                @if($student['status'] == 1)
                                <p style="color: green">Diterima</p>
                                @elseif($student['status'] == 2)
                                <p style="color: red">Ditolak</p>
                                @else
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('validasi', $student->nist) }}" method="POST" class="mr-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-validasi">Validasi</button>
                                    </form>
                                    <form action="{{ route('tolak', $student->nist) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger mx-1">Tolak</button>
                                    </form>
                                </div>
                                @endif
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
            window.location.href = `{{ url('/dashboard/pembayaran/pembayaran?perPage=${perPage}') }}`;
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

        $(document).ready(function() {
            $('#submitBtn').click(function() {
                $(this).prop('disabled', true); // Disable the button
                $(this).html('<span style="color:white;">Loading...</span>'); // Change button text to "Loading..."
                // Submit the form
                $(this).closest('form').submit();
            });
        });
    </script>

    <!-- Card untuk konten utama -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Pembayaran Infaq</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
                <!-- <a href="{{ route('create_pembayaran_admin') }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-plus"></i> Tambah
                </a> -->
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infaq_perbulan as $infaq_perbulan)
                        @if($infaq_perbulan['status'] == 0)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach($user as $item3)
                            @endforeach
                            <td>{{ $infaq_perbulan->siswa->user->nama }}</td>
                            <td>
                                <a href="{{ route('bukti_infaq', $infaq_perbulan->nist) }}">Lihat Bukti Pembayaran</a>
                            </td>
                            <td>
                                <a href="{{ route('detail.pendaftaran', $infaq_perbulan->nist) }}">Detail Pendaftaran</a>
                            </td>
                            <td style="vertical-align: middle;">
                                @if($infaq_perbulan['status'] == 1)
                                <p style="color: green">Diterima</p>
                                @elseif($infaq_perbulan['status'] == 2)
                                <p style="color: red">Ditolak</p>
                                @else
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('validasiinfaq', $infaq_perbulan->id_infaq) }}" method="POST" class="mr-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary btn-validasi">Validasi</button>
                                    </form>
                                    <form action="{{ route('tolakinfaq', $infaq_perbulan->id_infaq) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger mx-1">Tolak</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-size">
                        <select class="form-select form-select-sm" id="jumlahinfaq" onchange="changePerPageInfaq()">
                            <option value="5" {{ $perPageInfaq == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPageInfaq == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPageInfaq == 20 ? 'selected' : '' }}>20</option>
                            <option value="all" {{ $perPageInfaq == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <!-- Tempatkan komponen lain untuk navigasi pagination di sini jika diperlukan -->

                </div>
            </div>
        </div>
    </div>

    <script>
        function changePerPageInfaq() {
            var perPageInfaq = document.getElementById('jumlahinfaq').value;
            window.location.href = `{{ url('/dashboard/pembayaran/pembayaran?perPageInfaq=${perPageInfaq}') }}`;
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
    <div class="card-header">
        <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
    </div>
</div>
<div class="card-content">
    <div class="card-body">
        <div class="form-body">
            <form method="POST" action="{{ route('postPayment') }}" enctype="multipart/form-data">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @csrf

                <!-- Kolom Bank Tujuan, Nama Pemilik Rekening, dan No. Rekening -->
                <div id="bank_details" class="col-md-12">
                    <div class="row">
                        <!-- Bank Tujuan -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_bank">Bank Tujuan</label>
                                <select id="id_bank" name="id_bank" class="form-control">
                                    <option hidden disabled selected>--Pilih Bank--</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->nama }}" data-pemilik="{{ $bank->atas_nama }}" data-rekening="{{ $bank->no_rekening }}" data-phone="{{ $bank->phone }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nama Pemilik Rekening -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pemilik_rekening">Nama Pemilik Rekening</label>
                                <input type="text" id="pemilik_rekening" class="form-control" name="pemilik_rekening" readonly>
                            </div>
                        </div>
                        <!-- No. Rekening -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_rekening">No. Rekening</label>
                                <input type="text" id="no_rekening" class="form-control" name="no_rekening" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Insert an HTML line break here --}}
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                {{-- Continue with the rest of your table --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank">Nama Bank Pengirim</label>
                            <select id="nama_bank" name="nama_bank" class="form-control" onchange='checkvalue(this.value)'>
                                <option hidden disabled selected style="padding: 20px">--Pilih Bank--</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                                @endforeach
                                <option value="bank_lainnya">LAINNYA</option>
                            </select>
                        </div>
                        <div id="bank_lainnya" style="display: none">
                            <div class="form-group">
                                <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet Digital</label>
                                <input type="text" name="nama_bank_lainnya" id="nama_bank_text" class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Pemilik Rekening</label>
                            <input type="text" class="form-control" name="pemilik_rekening" value="" required>
                        </div>
                        <div class="form-group">
                            <label>Pilih Paket Tamyiz</label>
                            <select name="jumlah_pembayaran" id="durasi_pembayaran" class="form-control">
                                <option value="">Pilih</option>
                                @foreach($periode_pendaftaran as $item)
                                @php
                                $label = '';
                                if ($item->periode % 12 == 0) {
                                $label = ($item->periode / 12) . ' Tahun';
                                } else {
                                $label = $item->periode . ' Bulan';
                                }
                                @endphp
                                <option value="{{ $item->periode }}" data-periode_id="{{ $item->id_periodedaftar }}" data-diskon="{{ $item->diskon }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_periodedaftar" id="id_periodedaftar" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nominal</label>
                            @foreach($programs as $program)
                            @php
                            $nominalAwal = $program->nominal;
                            @endphp
                            @endforeach
                            <input type="text" id="nominal" class="form-control" name="nominal" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" required readonly>
                        </div>
                        <div id="nominal_no_discount_container" class="form-group" style="display: none;">
                            <label>Nominal Tanpa Diskon</label>
                            <input type="text" id="nominal_no_discount" class="form-control" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" readonly>
                        </div>

                        <!-- Form group for the nominal value with discount -->
                        <div id="nominal_with_discount_container" class="form-group" style="display: none;">
                            <label>Nominal Dengan Diskon</label>
                            <input type="text" id="nominal_with_discount" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Infaq</label>
                            <select name="infaq_status" id="infaq_status" class="form-control">
                                <option value="">Pilih</option>
                                <option value="1">Ingin Berinfaq</option>
                                <option value="0">Tidak Ingin Berinfaq</option>
                            </select>
                        </div>
                        <div id="infaq_input" style="display: none;">
                            <div class="form-group">
                                <label>Nominal Infaq</label>
                                <input type="text" name="infaq" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_upload">Bukti Pembayaran</label>
                            <input type="file" name="img_bukti" id="img_bukti" class="form-control" placeholder="Masukkan Bukti Pembayaran" value="" required autocomplete="img_bukti" accept=".jpg,.png">
                        </div>
                        <div class="row align-items-start">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <input type="submit" id="submitBtn" value="Upload Bukti" class="btn btn-block" style="background-color: #1c2558;color:white;">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Simpan nilai asli dari pilihan paket tamyiz saat halaman dimuat
    var originalTamyizValue = document.getElementById('durasi_pembayaran').value;

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            infaq = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            infaq += separator + ribuan.join('.');
        }

        // Batas maksimal 30 digit (sampai 1 triliun)
        infaq = infaq.substr(0, 30);

        infaq = split[1] !== undefined ? infaq + ',' + split[1].substr(0, 2) : infaq;

        return infaq;
    }

    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaq = document.getElementById('infaq');
        infaq.value = formatRupiah(infaq.value);
    });

    // Event listener for the infaq input field
    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaqValue = this.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
        if (infaqValue) {
            infaqValue = parseInt(infaqValue); // Convert infaq value to integer
        } else {
            infaqValue = 0; // Default infaq value to 0 if empty
        }

        var selectedOption = document.getElementById('durasi_pembayaran').selectedOptions[0];
        var durasi = parseInt(selectedOption.value) || 0; // Get selected duration or default to 0
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0; // Get discount or default to 0
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, '')); // Get initial nominal value

        var totalTanpaDiskon = nominalAwal * (durasi || 1); // Calculate total without discount
        var totalDiskon = totalTanpaDiskon * (diskon / 100); // Calculate discount amount
        var totalSetelahDiskon = totalTanpaDiskon - totalDiskon; // Calculate total after discount

        // Calculate the new total including infaq
        var totalDenganInfaq = totalSetelahDiskon + infaqValue; // Add infaq to total after discount
        document.getElementById('nominal').value = 'Rp. ' + formatRupiah(totalDenganInfaq.toString()); // Update the 'Nominal' field
    });




    $(document).ready(function() {
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));

        $('#infaq_status').change(function() {
            if ($(this).val() == '1') {
                $("#infaq_input").show();
            } else {
                $("#infaq_input").hide();

                $('input[name="nominal"]').val('Rp. ' + nominalAwal.toLocaleString());
            }
        });
    });

    document.getElementById('durasi_pembayaran').addEventListener('change', function(e) {
        var selectedOption = this.options[this.selectedIndex];
        var durasi = parseInt(selectedOption.value);
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0;
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));
        var infaqElement = document.getElementById('infaq');
        var infaqValue = infaqElement.value.replace(/[^\d]/g, '') || 0;

        // Perubahan di sini: Set value untuk id_periodedaftar
        document.getElementById('id_periodedaftar').value = selectedOption.getAttribute('data-periode_id');

        if (selectedOption.value === '') {
            // If the "choose" option is selected, reset to the default nominal
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(nominalAwal.toString()));
        } else if (!isNaN(durasi)) {
            // Continue with calculation if a valid duration is selected
            var totalTanpaDiskon = nominalAwal * durasi;
            var totalDiskon = totalTanpaDiskon * (diskon / 100);
            var totalSetelahDiskon = totalTanpaDiskon - totalDiskon;

            // Update the 'Nominal' field with the total after discount
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalSetelahDiskon.toString()));

            // If there's an infaq value, add it to the total after discount
            if (infaqValue !== 0) {
                infaqValue = parseFloat(infaqValue);
                var totalDenganInfaq = totalSetelahDiskon + infaqValue;
                $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalDenganInfaq.toString()));
            }
        }
    });



    document.getElementById('id_bank').addEventListener('change', function() {
        var selectedBank = this.options[this.selectedIndex];
        var bankDetails = document.getElementById('bank_details');
        var pemilikRekeningInput = bankDetails.querySelector('input[name="pemilik_rekening"]');
        var noRekeningInput = bankDetails.querySelector('input[name="no_rekening"]');

        if (selectedBank.value !== '') {
            pemilikRekeningInput.value = selectedBank.getAttribute('data-pemilik');
            noRekeningInput.value = selectedBank.getAttribute('data-rekening');
            bankDetails.style.display = 'block';
        } else {
            // Jika tidak ada bank yang dipilih, sembunyikan detail bank
            bankDetails.style.display = 'none';
            pemilikRekeningInput.value = '';
            noRekeningInput.value = '';
        }
    });

    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $(this).prop('disabled', true); // Disable the button
            $(this).html('<span style="color:white;">Loading...</span>'); // Change button text to "Loading..."
            // Submit the form
            $(this).closest('form').submit();
        });
    });
</script>

@elseif($item->status == 0)
<div class="card-content">
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Pembayaran telah dilakukan!</span>
        </div>
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Sedang munggu pembayaran dikonfirmasi!</span>
        </div>
    </div>
</div>
@elseif($item->status == 1)
<div class="card-content">
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Pembayaran telah dikonfirmasi!</span>
        </div>
    </div>
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
        rupiah = rupiah.substr(0, 20);

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1].substr(0, 2) : rupiah;

        return 'Rp ' + rupiah;
    }

    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaq = document.getElementById('infaq');
        infaq.value = formatRupiah(infaq.value);
    });

    document.getElementById('id_bank').addEventListener('change', function() {
        var selectedBank = this.options[this.selectedIndex];
        var bankDetails = document.getElementById('bank_details');
        var pemilikRekeningInput = bankDetails.querySelector('input[name="pemilik_rekening"]');
        var noRekeningInput = bankDetails.querySelector('input[name="no_rekening"]');

        if (selectedBank.value !== '') {
            pemilikRekeningInput.value = selectedBank.getAttribute('data-pemilik');
            noRekeningInput.value = selectedBank.getAttribute('data-rekening');
            bankDetails.style.display = 'block';
        } else {
            // Jika tidak ada bank yang dipilih, sembunyikan detail bank
            bankDetails.style.display = 'none';
            pemilikRekeningInput.value = '';
            noRekeningInput.value = '';
        }
    });

    // Fungsi untuk memformat input ke format rupiah
    function formatRupiah(element) {
        let input = element.value.replace(/\D/g, ''); // Hapus semua karakter kecuali angka
        let formatted = '';

        while (input.length > 3) {
            formatted = '.' + input.substr(input.length - 3) + formatted;
            input = input.substring(0, input.length - 3);
        }

        element.value = input ? input + formatted : '';
    }
</script>

@elseif($item->status == 3)
<div class="card-header">
    <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
</div>
</div>
<div class="card-content">
    <div class="card-body">
        <div class="form-body">
            <form method="POST" action="{{ route('postPayment') }}" enctype="multipart/form-data">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @csrf

                <!-- Kolom Bank Tujuan, Nama Pemilik Rekening, dan No. Rekening -->
                <div id="bank_details" class="col-md-12">
                    <div class="row">
                        <!-- Bank Tujuan -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_bank">Bank Tujuan</label>
                                <select id="id_bank" name="id_bank" class="form-control">
                                    <option hidden disabled selected>--Pilih Bank--</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->nama }}" data-pemilik="{{ $bank->atas_nama }}" data-rekening="{{ $bank->no_rekening }}" data-phone="{{ $bank->phone }}">{{ $bank->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nama Pemilik Rekening -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pemilik_rekening">Nama Pemilik Rekening</label>
                                <input type="text" id="pemilik_rekening" class="form-control" name="pemilik_rekening" readonly>
                            </div>
                        </div>
                        <!-- No. Rekening -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_rekening">No. Rekening</label>
                                <input type="text" id="no_rekening" class="form-control" name="no_rekening" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Insert an HTML line break here --}}
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                {{-- Continue with the rest of your table --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank">Nama Bank Pengirim</label>
                            <select id="nama_bank" name="nama_bank" class="form-control" onchange='checkvalue(this.value)'>
                                <option hidden disabled selected style="padding: 20px">--Pilih Bank--</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                                @endforeach
                                <option value="bank_lainnya">LAINNYA</option>
                            </select>
                        </div>
                        <div id="bank_lainnya" style="display: none">
                            <div class="form-group">
                                <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet Digital</label>
                                <input type="text" name="nama_bank_lainnya" id="nama_bank_text" class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nama Pemilik Rekening</label>
                            <input type="text" class="form-control" name="pemilik_rekening" value="" required>
                        </div>
                        <div class="form-group">
                            <label>Pilih Paket Tamyiz</label>
                            <select name="jumlah_pembayaran" id="durasi_pembayaran" class="form-control">
                                <option value="">Pilih</option>
                                @foreach($periode_pendaftaran as $item)
                                @php
                                $label = '';
                                if ($item->periode % 12 == 0) {
                                $label = ($item->periode / 12) . ' Tahun';
                                } else {
                                $label = $item->periode . ' Bulan';
                                }
                                @endphp
                                <option value="{{ $item->periode }}" data-periode_id="{{ $item->id_periodedaftar }}" data-diskon="{{ $item->diskon }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id_periodedaftar" id="id_periodedaftar" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nominal</label>
                            @foreach($programs as $program)
                            @php
                            $nominalAwal = $program->nominal;
                            @endphp
                            @endforeach
                            <input type="text" id="nominal" class="form-control" name="nominal" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" required readonly>
                        </div>
                        <div id="nominal_no_discount_container" class="form-group" style="display: none;">
                            <label>Nominal Tanpa Diskon</label>
                            <input type="text" id="nominal_no_discount" class="form-control" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" readonly>
                        </div>

                        <!-- Form group for the nominal value with discount -->
                        <div id="nominal_with_discount_container" class="form-group" style="display: none;">
                            <label>Nominal Dengan Diskon</label>
                            <input type="text" id="nominal_with_discount" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Infaq</label>
                            <select name="infaq_status" id="infaq_status" class="form-control">
                                <option value="">Pilih</option>
                                <option value="1">Ingin Berinfaq</option>
                                <option value="0">Tidak Ingin Berinfaq</option>
                            </select>
                        </div>
                        <div id="infaq_input" style="display: none;">
                            <div class="form-group">
                                <label>Nominal Infaq</label>
                                <input type="text" name="infaq" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_upload">Bukti Pembayaran</label>
                            <input type="file" name="img_bukti" id="img_bukti" class="form-control" placeholder="Masukkan Bukti Pembayaran" value="" required autocomplete="img_bukti" accept=".jpg,.png">
                        </div>
                        <div class="row align-items-start">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <input type="submit" id="submitBtn" value="Upload Bukti" class="btn btn-block" style="background-color: #1c2558;color:white;">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Simpan nilai asli dari pilihan paket tamyiz saat halaman dimuat
    var originalTamyizValue = document.getElementById('durasi_pembayaran').value;

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            infaq = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            infaq += separator + ribuan.join('.');
        }

        // Batas maksimal 30 digit (sampai 1 triliun)
        infaq = infaq.substr(0, 30);

        infaq = split[1] !== undefined ? infaq + ',' + split[1].substr(0, 2) : infaq;

        return infaq;
    }

    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaq = document.getElementById('infaq');
        infaq.value = formatRupiah(infaq.value);
    });

    // Event listener for the infaq input field
    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaqValue = this.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
        if (infaqValue) {
            infaqValue = parseInt(infaqValue); // Convert infaq value to integer
        } else {
            infaqValue = 0; // Default infaq value to 0 if empty
        }

        var selectedOption = document.getElementById('durasi_pembayaran').selectedOptions[0];
        var durasi = parseInt(selectedOption.value) || 0; // Get selected duration or default to 0
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0; // Get discount or default to 0
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, '')); // Get initial nominal value

        var totalTanpaDiskon = nominalAwal * (durasi || 1); // Calculate total without discount
        var totalDiskon = totalTanpaDiskon * (diskon / 100); // Calculate discount amount
        var totalSetelahDiskon = totalTanpaDiskon - totalDiskon; // Calculate total after discount

        // Calculate the new total including infaq
        var totalDenganInfaq = totalSetelahDiskon + infaqValue; // Add infaq to total after discount
        document.getElementById('nominal').value = 'Rp. ' + formatRupiah(totalDenganInfaq.toString()); // Update the 'Nominal' field
    });




    $(document).ready(function() {
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));

        $('#infaq_status').change(function() {
            if ($(this).val() == '1') {
                $("#infaq_input").show();
            } else {
                $("#infaq_input").hide();

                $('input[name="nominal"]').val('Rp. ' + nominalAwal.toLocaleString());
            }
        });
    });

    document.getElementById('durasi_pembayaran').addEventListener('change', function(e) {
        var selectedOption = this.options[this.selectedIndex];
        var durasi = parseInt(selectedOption.value);
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0;
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));
        var infaqElement = document.getElementById('infaq');
        var infaqValue = infaqElement.value.replace(/[^\d]/g, '') || 0;

        // Perubahan di sini: Set value untuk id_periodedaftar
        document.getElementById('id_periodedaftar').value = selectedOption.getAttribute('data-periode_id');

        if (selectedOption.value === '') {
            // If the "choose" option is selected, reset to the default nominal
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(nominalAwal.toString()));
        } else if (!isNaN(durasi)) {
            // Continue with calculation if a valid duration is selected
            var totalTanpaDiskon = nominalAwal * durasi;
            var totalDiskon = totalTanpaDiskon * (diskon / 100);
            var totalSetelahDiskon = totalTanpaDiskon - totalDiskon;

            // Update the 'Nominal' field with the total after discount
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalSetelahDiskon.toString()));

            // If there's an infaq value, add it to the total after discount
            if (infaqValue !== 0) {
                infaqValue = parseFloat(infaqValue);
                var totalDenganInfaq = totalSetelahDiskon + infaqValue;
                $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalDenganInfaq.toString()));
            }
        }
    });



    document.getElementById('id_bank').addEventListener('change', function() {
        var selectedBank = this.options[this.selectedIndex];
        var bankDetails = document.getElementById('bank_details');
        var pemilikRekeningInput = bankDetails.querySelector('input[name="pemilik_rekening"]');
        var noRekeningInput = bankDetails.querySelector('input[name="no_rekening"]');

        if (selectedBank.value !== '') {
            pemilikRekeningInput.value = selectedBank.getAttribute('data-pemilik');
            noRekeningInput.value = selectedBank.getAttribute('data-rekening');
            bankDetails.style.display = 'block';
        } else {
            // Jika tidak ada bank yang dipilih, sembunyikan detail bank
            bankDetails.style.display = 'none';
            pemilikRekeningInput.value = '';
            noRekeningInput.value = '';
        }
    });

    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $(this).prop('disabled', true); // Disable the button
            $(this).html('<span style="color:white;">Loading...</span>'); // Change button text to "Loading..."
            // Submit the form
            $(this).closest('form').submit();
        });
    });
</script>

@elseif($item->status == 2)
{{-- tampilin form lagi --}}
<div class="card-content">
    <div class="card-body">
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Pembayaran sebelumnya ditolak oleh admin, silahkan upload pembayaran lagi</span>
        </div>
    </div>
    <div class="card-header">
        <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
    </div>
</div>
<div class="card-content">
    <div class="card-body">
        <form class="form form-vertical" method="POST" action="{{ route('pembayaran.update') }}" enctype="multipart/form-data">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @csrf
            @method('PATCH')
            <!-- Kolom Bank Tujuan, Nama Pemilik Rekening, dan No. Rekening -->
            <div id="bank_details" class="col-md-12">
                <div class="row">
                    <!-- Bank Tujuan -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_bank">Bank Tujuan</label>
                            <select id="id_bank" name="id_bank" class="form-control">
                                <option hidden disabled selected>--Pilih Bank--</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->nama }}" data-pemilik="{{ $bank->atas_nama }}" data-rekening="{{ $bank->no_rekening }}" data-phone="{{ $bank->phone }}">{{ $bank->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Nama Pemilik Rekening -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pemilik_rekening">Nama Pemilik Rekening</label>
                            <input type="text" id="pemilik_rekening" class="form-control" name="pemilik_rekening" readonly>
                        </div>
                    </div>
                    <!-- No. Rekening -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_rekening">No. Rekening</label>
                            <input type="text" id="no_rekening" class="form-control" name="no_rekening" readonly>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Insert an HTML line break here --}}
            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>
            {{-- Continue with the rest of your table --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bank">Nama Bank Pengirim</label>
                        <select id="nama_bank" name="nama_bank" class="form-control" onchange='checkvalue(this.value)'>
                            <option hidden disabled selected style="padding: 20px">--Pilih Bank--</option>
                            @foreach($banks as $bank)
                            <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                            @endforeach
                            <option value="bank_lainnya">LAINNYA</option>
                        </select>
                    </div>
                    <div id="bank_lainnya" style="display: none">
                        <div class="form-group">
                            <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet Digital</label>
                            <input type="text" name="nama_bank_lainnya" id="nama_bank_text" class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" name="pemilik_rekening" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Pilih Paket Tamyiz</label>
                        <select name="jumlah_pembayaran" id="durasi_pembayaran" class="form-control">
                            <option value="">Pilih</option>
                            @foreach($periode_pendaftaran as $item)
                            @php
                            $label = '';
                            if ($item->periode % 12 == 0) {
                            $label = ($item->periode / 12) . ' Tahun';
                            } else {
                            $label = $item->periode . ' Bulan';
                            }
                            @endphp
                            <option value="{{ $item->periode }}" data-periode_id="{{ $item->id_periodedaftar }}" data-diskon="{{ $item->diskon }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="id_periodedaftar" id="id_periodedaftar" value="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nominal</label>
                        @foreach($programs as $program)
                        @php
                        $nominalAwal = $program->nominal;
                        @endphp
                        @endforeach
                        <input type="text" id="nominal" class="form-control" name="nominal" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" required readonly>
                    </div>
                    <div id="nominal_no_discount_container" class="form-group" style="display: none;">
                        <label>Nominal Tanpa Diskon</label>
                        <input type="text" id="nominal_no_discount" class="form-control" value="{{ 'Rp. '.number_format($nominalAwal, 0, ',', '.') }}" readonly>
                    </div>

                    <!-- Form group for the nominal value with discount -->
                    <div id="nominal_with_discount_container" class="form-group" style="display: none;">
                        <label>Nominal Dengan Diskon</label>
                        <input type="text" id="nominal_with_discount" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Infaq</label>
                        <select name="infaq_status" id="infaq_status" class="form-control">
                            <option value="">Pilih</option>
                            <option value="1">Ingin Berinfaq</option>
                            <option value="0">Tidak Ingin Berinfaq</option>
                        </select>
                    </div>
                    <div id="infaq_input" style="display: none;">
                        <div class="form-group">
                            <label>Nominal Infaq</label>
                            <input type="text" name="infaq" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image_upload">Bukti Pembayaran</label>
                        <input type="file" name="img_bukti" id="img_bukti" class="form-control" placeholder="Masukkan Bukti Pembayaran" value="" required autocomplete="img_bukti" accept=".jpg,.png">
                    </div>
                    <div class="row align-items-start">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <input type="submit" value="Upload Bukti" class="btn btn-block" style="background-color: #1c2558;color:white;">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    // Simpan nilai asli dari pilihan paket tamyiz saat halaman dimuat
    var originalTamyizValue = document.getElementById('durasi_pembayaran').value;

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            infaq = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            infaq += separator + ribuan.join('.');
        }

        // Batas maksimal 30 digit (sampai 1 triliun)
        infaq = infaq.substr(0, 30);

        infaq = split[1] !== undefined ? infaq + ',' + split[1].substr(0, 2) : infaq;

        return infaq;
    }

    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaq = document.getElementById('infaq');
        infaq.value = formatRupiah(infaq.value);
    });

    // Event listener for the infaq input field
    document.getElementById('infaq').addEventListener('keyup', function(e) {
        var infaqValue = this.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
        if (infaqValue) {
            infaqValue = parseInt(infaqValue); // Convert infaq value to integer
        } else {
            infaqValue = 0; // Default infaq value to 0 if empty
        }

        var selectedOption = document.getElementById('durasi_pembayaran').selectedOptions[0];
        var durasi = parseInt(selectedOption.value) || 0; // Get selected duration or default to 0
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0; // Get discount or default to 0
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, '')); // Get initial nominal value

        var totalTanpaDiskon = nominalAwal * (durasi || 1); // Calculate total without discount
        var totalDiskon = totalTanpaDiskon * (diskon / 100); // Calculate discount amount
        var totalSetelahDiskon = totalTanpaDiskon - totalDiskon; // Calculate total after discount

        // Calculate the new total including infaq
        var totalDenganInfaq = totalSetelahDiskon + infaqValue; // Add infaq to total after discount
        document.getElementById('nominal').value = 'Rp. ' + formatRupiah(totalDenganInfaq.toString()); // Update the 'Nominal' field
    });




    $(document).ready(function() {
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));

        $('#infaq_status').change(function() {
            if ($(this).val() == '1') {
                $("#infaq_input").show();
            } else {
                $("#infaq_input").hide();

                $('input[name="nominal"]').val('Rp. ' + nominalAwal.toLocaleString());
            }
        });
    });

    document.getElementById('durasi_pembayaran').addEventListener('change', function(e) {
        var selectedOption = this.options[this.selectedIndex];
        var durasi = parseInt(selectedOption.value);
        var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) || 0;
        var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g, ''));
        var infaqElement = document.getElementById('infaq');
        var infaqValue = infaqElement.value.replace(/[^\d]/g, '') || 0;

        // Perubahan di sini: Set value untuk id_periodedaftar
        document.getElementById('id_periodedaftar').value = selectedOption.getAttribute('data-periode_id');

        if (selectedOption.value === '') {
            // If the "choose" option is selected, reset to the default nominal
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(nominalAwal.toString()));
        } else if (!isNaN(durasi)) {
            // Continue with calculation if a valid duration is selected
            var totalTanpaDiskon = nominalAwal * durasi;
            var totalDiskon = totalTanpaDiskon * (diskon / 100);
            var totalSetelahDiskon = totalTanpaDiskon - totalDiskon;

            // Update the 'Nominal' field with the total after discount
            $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalSetelahDiskon.toString()));

            // If there's an infaq value, add it to the total after discount
            if (infaqValue !== 0) {
                infaqValue = parseFloat(infaqValue);
                var totalDenganInfaq = totalSetelahDiskon + infaqValue;
                $('input[name="nominal"]').val('Rp. ' + formatRupiah(totalDenganInfaq.toString()));
            }
        }
    });



    document.getElementById('id_bank').addEventListener('change', function() {
        var selectedBank = this.options[this.selectedIndex];
        var bankDetails = document.getElementById('bank_details');
        var pemilikRekeningInput = bankDetails.querySelector('input[name="pemilik_rekening"]');
        var noRekeningInput = bankDetails.querySelector('input[name="no_rekening"]');

        if (selectedBank.value !== '') {
            pemilikRekeningInput.value = selectedBank.getAttribute('data-pemilik');
            noRekeningInput.value = selectedBank.getAttribute('data-rekening');
            bankDetails.style.display = 'block';
        } else {
            // Jika tidak ada bank yang dipilih, sembunyikan detail bank
            bankDetails.style.display = 'none';
            pemilikRekeningInput.value = '';
            noRekeningInput.value = '';
        }
    });
</script>
</div>
</form>
</div>
@endif
@endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script>
    function checkvalue() {
        var bank = document.getElementById("nama_bank").value;
        var bankLainnya = document.getElementById("bank_lainnya");
        if (bank === "bank_lainnya") {
            bankLainnya.style.display = 'block';
        } else {
            bankLainnya.style.display = 'none';
        }
    }

    var rupiah = document.getElementById("nominal");
    rupiah.addEventListener("keyup", function(e) {
        rupiah.value = formatRupiah(this.value, "Rp. ");
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }


    $(".select2").select2();
</script>


@endsection