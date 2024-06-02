@extends('layout')
@section('breadcrumb')
    Tambah Pembayaran
@endsection
@section('judul')
    Tambah Pembayaran
@endsection
@section('content')
    @include('sweetalert::alert')


    <div style="">
        @if (Auth::user()->role == 'admin')
            <!-- tambahkan kondisi untuk menampilkan pesan -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <!-- tambahkan kondisi untuk menampilkan pesan error -->
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Pembayaran</h5>
                    <a href="{{ route('bank.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{ route('update_pembayaran_admin') }}" enctype="multipart/form-data">
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
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->nama }}"
                                                        data-pemilik="{{ $bank->atas_nama }}"
                                                        data-rekening="{{ $bank->no_rekening }}"
                                                        data-phone="{{ $bank->phone }}">{{ $bank->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Nama Pemilik Rekening -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pemilik_rekening">Nama Pemilik Rekening</label>
                                            <input type="text" id="pemilik_rekening" class="form-control"
                                                name="pemilik_rekening" readonly>
                                        </div>
                                    </div>
                                    <!-- No. Rekening -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="no_rekening">No. Rekening</label>
                                            <input type="text" id="no_rekening" class="form-control" name="no_rekening"
                                                readonly>
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
                                    <!-- Dropdown Nama Siswa -->
                                    <div class="form-group">
                                        <label for="nist">Nama Santri</label>
                                        <select id="nist" name="nist" class="form-control">
                                            <option hidden disabled selected style="padding: 20px">--Pilih Santri--</option>
                                            @foreach ($siswa as $s)
                                                <!-- Gabungkan nist dan nama siswa -->
                                                <option value="{{ $s->nist }}">{{ $s->nist }} -
                                                    {{ $s->user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="bank">Nama Bank Pengirim</label>
                                        <select id="nama_bank" name="nama_bank" class="form-control"
                                            onchange='checkvalue(this.value)'>
                                            <option hidden disabled selected style="padding: 20px">--Pilih Bank--</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                                            @endforeach
                                            <option value="bank_lainnya">LAINNYA</option>
                                        </select>
                                    </div>
                                    <div id="bank_lainnya" style="display: none">
                                        <div class="form-group">
                                            <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet
                                                Digital</label>
                                            <input type="text" name="nama_bank_lainnya" id="nama_bank_text"
                                                class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control" name="pemilik_rekening" value=""
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih Paket Tamyiz</label>
                                        <select name="jumlah_pembayaran" id="durasi_pembayaran" class="form-control">
                                            <option value="">Pilih</option>
                                            @foreach ($periode_pendaftaran as $item)
                                                @php
                                                    $label = '';
                                                    if ($item->periode % 12 == 0) {
                                                        $label = $item->periode / 12 . ' Tahun';
                                                    } else {
                                                        $label = $item->periode . ' Bulan';
                                                    }
                                                @endphp
                                                <option value="{{ $item->periode }}"
                                                    data-periode_id="{{ $item->id_periodedaftar }}"
                                                    data-diskon="{{ $item->diskon }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="id_periodedaftar" id="id_periodedaftar" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nominal</label>
                                        @foreach ($programs as $program)
                                            @php
                                                $nominalAwal = $program->nominal;
                                            @endphp
                                        @endforeach
                                        <input type="text" id="nominal" class="form-control" name="nominal"
                                            value="{{ 'Rp. ' . number_format($nominalAwal, 0, ',', '.') }}" required
                                            readonly>
                                    </div>
                                    <div id="nominal_no_discount_container" class="form-group" style="display: none;">
                                        <label>Nominal Tanpa Diskon</label>
                                        <input type="text" id="nominal_no_discount" class="form-control"
                                            value="{{ 'Rp. ' . number_format($nominalAwal, 0, ',', '.') }}" readonly>
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
                                            <input type="text" name="infaq" class="form-control" id="infaq"
                                                placeholder="Masukkan Nominal Infaq">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image_upload">Pilih Gambar Bukti Pembayaran</label>
                                        <input type="file" name="img_bukti" id="img_bukti" class="form-control"
                                            placeholder="Masukkan Bukti Pembayaran" value="" required
                                            autocomplete="img_bukti" accept=".jpg,.png">
                                    </div>
                                    <div class="row align-items-start">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <input type="submit" value="Upload Bukti" class="btn btn-block"
                                                style="background-color: #1c2558;color:white;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
        @endif
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
            var diskon = parseFloat(selectedOption.getAttribute('data-diskon')) ||
            0; // Get discount or default to 0
            var nominalAwal = parseFloat('{{ $nominalAwal }}'.replace(/[^\d.-]/g,
            '')); // Get initial nominal value

            var totalTanpaDiskon = nominalAwal * (durasi || 1); // Calculate total without discount
            var totalDiskon = totalTanpaDiskon * (diskon / 100); // Calculate discount amount
            var totalSetelahDiskon = totalTanpaDiskon - totalDiskon; // Calculate total after discount

            // Calculate the new total including infaq
            var totalDenganInfaq = totalSetelahDiskon + infaqValue; // Add infaq to total after discount
            document.getElementById('nominal').value = 'Rp. ' + formatRupiah(totalDenganInfaq
        .toString()); // Update the 'Nominal' field
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
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
