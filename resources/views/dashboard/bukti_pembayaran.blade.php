@extends('layout')
@section('breadcrumb')
Bukti Pembayaran
@endsection
@section('title')
Bukti Pembayaran
@endsection
@section('content')
@include('sweetalert::alert')

<style>
    .centered-input {
        text-align: center;
    }
</style>


<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Bukti pendaftaran {{$detailSiswa->user->nama}}</span>
        </div>
    </div>
</div>
<div class="row">
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
    <div class="col-md-8 mx-auto">
        <div class="card mb-5 mt-3 p-3 g-2 d-flex" style="background-color: #ffffff; box-shadow: 0px 4px 11px 0px #e1e1e1;">
            <div class="detail mt-2 card-body" style="color:#1c2558;">
                <div class="text-center"> <!-- Container untuk align center -->
                    <img src="{{asset('buktipembayaran/'.$pay->img_bukti)}}" style="max-width: 100%; height: auto;" alt="" class="mx-auto d-block">
                </div>
                <ul class="list-group">
                    <table cellspacing="0" cellpadding="4" class="text-center mt-4">
                        @foreach ($bank as $item)
                        @endforeach
                        <tr>
                            <th width="50%">Bank Tujuan</th>
                            <td>:</td>
                            <td>{{ $item->nama }}</td>
                        </tr>
                        <tr>
                            <th width="50%">No Rekening</th>
                            <td>:</td>
                            <td>{{ $item->no_rekening }}</td>
                        </tr>
                        <tr>
                            <th width="50%">Nama Rekening</th>
                            <td>:</td>
                            <td>{{ $item->atas_nama }}</td>
                        </tr>
                        {{-- Insert an HTML line break here --}}
                        <tr>
                            <td colspan="3">
                                <hr>
                            </td>
                        </tr>
                        {{-- Continue with the rest of your table --}}
                        <tr>
                            <th width="50%">Bank Pengirim</th>
                            <td>:</td>
                            <td>{{ $pay->nama_bank }}</td>
                        </tr>
                        <tr>
                            <th width="50%">Nama rekening</th>
                            <td>:</td>
                            <td>{{ $pay->pemilik_rekening }}</td>
                        </tr>
                        <form method="POST" action="{{ route('update_nominal', $detailSiswa->nist) }}">
                            @csrf
                            @method('patch')
                            <tr>
                                <th width="50%">Nominal</th>
                                <td>:</td>
                                <td class="text-center">
                                    <div class="d-inline-block" style="width: 50%;">
                                        <!-- Modifikasi input field di sini -->
                                        <input type="text" name="nominal" id="nominal" value="{{ 'Rp ' . number_format($pay->nominal, 0, ',', '.') }}" class="form-control centered-input" onkeyup="formatRupiah(this)" required>
                                    </div>
                                    <div class="d-inline-block">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </td>
                            </tr>
                        </form>
                    </table>
                </ul>
            </div>
            <a href="/dashboard/pembayaran/pembayaran" class="mx-auto"><button class="btn bg-primary text-white">Kembali</button></a>
        </div>
    </div>
</div>
<script>
    function formatRupiah(element) {
        let numberString = element.value.replace(/[^,\d]/g, '').toString();
        let split = numberString.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        // Tambahkan 'Rp' di depan nilai rupiah
        element.value = 'Rp ' + rupiah;
    }
</script>
@endsection