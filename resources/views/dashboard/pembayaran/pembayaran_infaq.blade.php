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

    @if (!isset($iteminfaq))
    <div class="card-header">
        <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
    </div>
</div>
<div class="card-content">
    <div class="card-body">
        <div class="form-body">
            <form method="POST" action="{{ route('postInfaq') }}" enctype="multipart/form-data">
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
                            <label for="bank">Nama Bank</label>
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nominal Infaq</label>
                            <input type="text" name="nominal" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq" onkeyup="formatRupiah(this)">
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

@elseif($iteminfaq->status == 0)
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
@elseif($iteminfaq->status == 1)
<div class="card-content">
    <div class="card-body">
        <div class="alert alert-success" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Pembayaran telah dikonfirmasi!</span>
        </div>
    </div>
</div>
<div class="card-content">
    <div class="card-body">
        <div class="form-body">
            <form method="POST" action="{{ route('postInfaq') }}" enctype="multipart/form-data">
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
                            <label for="bank">Nama Bank</label>
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nominal Infaq</label>
                            <input type="text" name="nominal" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq" onkeyup="formatRupiah(this)">
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

    $(document).ready(function() {
        $('#submitBtn').click(function() {
            $(this).prop('disabled', true); // Disable the button
            $(this).html('<span style="color:white;">Loading...</span>'); // Change button text to "Loading..."
            // Submit the form
            $(this).closest('form').submit();
        });
    });
</script>


@elseif($iteminfaq->status == 2)
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
        <div class="form-body">
            <form class="form form-vertical" method="POST" action="{{ route('infaq.update') }}" enctype="multipart/form-data">
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
                            <label for="bank">Nama Bank</label>
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nominal Infaq</label>
                            <input type="text" name="nominal" class="form-control" id="infaq" placeholder="Masukkan Nominal Infaq" onkeyup="formatRupiah(this)">
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
</div>
</form>
</div>
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