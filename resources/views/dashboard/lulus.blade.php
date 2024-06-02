@extends('layout')
@section('breadcrumb')
Pembayaran
@endsection
@section('judul')
Pembayaran
@endsection
@section('content')

<div style="">
    @if(Auth::user()->role == 'admin')

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Silahkan verifikasi pembayaran siswa</h4>
        </div>
        <div class="card-content">
            <div class="card-body">

            </div>
            <!-- table responsive -->
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">No Registrasi</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Bukti Pembayaran</th>
                            <th scope="col">Detail Pendaftaran</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$student->siswa->id}}</td>
                            <td>{{$student->siswa->nama}}</td>
                            <td>
                                <a href="{{ route('bukti', $student->id_user) }}">Lihat</a>
                            </td>
                            <td> <a href="{{ route('detail.pendaftaran', $student->id_user) }}">Detail</a>
                            </td>
                            <td>
                                @if($student['status'] == 1)
                                <p style="color: green">Diterima</p>
                                @elseif($student['status'] == 2)
                                <p style="color: red">Ditolak</p>
                                @else
                                <div class="d-flex gap-2">
                                    <form action="{{ route('validasi', $student->id_user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary mr-3" style="color: white;">
                                            Validasi </button>
                                    </form>
                                    <form action="{{ route('tolak', $student->id_user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger" style="color: white">
                                            Tolak </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else

    @if (!isset($item))
    <div class="card-header">
        <h4 class="card-title">Silahkan isi form bukti pembayaran</h4>
    </div>
</div>
<div class="card-content">
    <div class="card-body">
        <div class="form-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
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
                            <div class="row">
                                <div class="col">
                                    <div class="kartu-pembayaran">
                                        <div class="card-body p-0 mt-3">
                                            <div class="row align-items-start">
                                                <div class="col-sm-4">
                                                    <label for="bank">Nama Bank</label>
                                                    <select id="bank" name="nama_bank" class="select2 form-control w-100 ml-0" onchange='checkvalue(this.value)'>
                                                        <option hidden disabled selected style="padding:20px">--Pilih
                                                            Bank--</option>
                                                        @foreach($banks as $bank)
                                                        <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                                                        @endforeach
                                                        <option value="bank_lainnya">LAINNYA</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Nama Pemilik Rekening</label>
                                                    <input type="text" class="form-control" name="pemilik_rekening" value="" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Nominal</label>
                                                    @foreach($programs as $program)
                                                    <input type="text" id="nominal" class="form-control" name="nominal" value="{{'Rp. '.number_format($program->nominal,0,',','.')}}" required readonly>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row" id="bank_lainnya" style="display: none">
                                                <div class="form-group col-md-12">
                                                    <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet
                                                        Digital</label>
                                                    <input type="text" name="nama_bank_lainnya" id="nama_bank_text" class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group mb-3">
                                                <label for="image_upload"> Choose Image</label>
                                                <input type="file" name="img_bukti" class="form-control" id="image_upload">
                                            </div>
                                            <br>
                                            <br>
                                            <div class="row align-items-start">
                                                <div class="col-md-8"></div>
                                                <div class="col-md-4">
                                                    <input type="submit" value="Upload Bukti Pembayaran" class="btn btn-block" style="background-color: #1c2558;color:white; ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
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
            <span>Pembayaran diverifikasi, silahkan lanjutkan ke tahap selanjutnya</span>
        </div>
    </div>
</div>
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
            <div class="form-body">
                <div class="row">
                    <div class="col">
                        <div class="kartu-pembayaran">
                            <div class="card-body p-0 mt-3">
                                <div class="row align-items-start">
                                    <div class="col-sm-4">
                                        <label for="bank">Nama Bank</label>
                                        <select id="bank" name="nama_bank" class="select2 form-control w-100 ml-0" onchange='checkvalue(this.value)'>
                                            <option hidden disabled selected style="padding:20px">--Pilih
                                                Bank--</option>
                                            @foreach($banks as $bank)
                                            <option value="{{ $bank->nama }}">{{ $bank->nama }}</option>
                                            @endforeach
                                            <option value="bank_lainnya">LAINNYA</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Nama Pemilik Rekening</label>
                                        <input type="text" class="form-control" name="pemilik_rekening" value="" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Nominal</label>
                                        @foreach($programs as $program)
                                        <input type="text" id="nominal" class="form-control" name="nominal" value="{{'Rp. '.number_format($program->nominal,0,',','.')}}" required readonly>
                                        @endforeach
                                    </div>
                                </div>
                                <br>
                                <div class="row" id="bank_lainnya" style="display: none">
                                    <div class="form-group col-md-12">
                                        <label for="nama_bank_lainnya" class="mb-2">Nama Bank atau Dompet
                                            Digital</label>
                                        <input type="text" name="nama_bank_lainnya" id="nama_bank_text" class="form-control" placeholder="Masukkan Nama Bank atau Dompet Digital">
                                    </div>
                                </div>
                                <br>
                                <div class="form-group mb-3">
                                    <label for="image_upload"> Choose Image</label>
                                    <input type="file" name="img_bukti" class="form-control" id="image_upload">
                                </div>
                                <br>
                                <br>
                                <div class="row align-items-start">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <input type="submit" value="Upload Bukti Pembayaran" class="btn btn-block" style="background-color: #1c2558;color:white; ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
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
        var bank = document.getElementById("bank").value;
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