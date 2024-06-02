@extends('layout')
@section('breadcrumb')
    Detail Pendaftaran
@endsection
@section('title')
    Detail Pendaftaran
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i>
            <span>Detail pendaftaran {{$detailSiswa->user->nama}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class=" mx-auto">
        <div class="card mb-5 mt-3 p-3 g-2 d-flex"
            style="background-color: #ffffff; box-shadow: 0px 4px 11px 0px #e1e1e1;">
            <div class="detail mt-2 card-body" style="color:#1c2558;">
                <ul class="list-group">
                    <table cellspacing="0" cellpadding="4" class="">
                        <tr>
                            <th width="50%">NIST</th>
                            <td>:</td>
                            <td>{{$detailSiswa->nist}}</td>
                        </tr>
                        <tr>
                            <th width="50%">Nama</th>
                            <td>:</td>
                            <td>{{$detailSiswa->user->nama}}</td>
                        </tr>
                        @foreach ($user as $item2)
                        @endforeach
                        <tr>
                            <th width="50%">Nomor Hp</th>
                            <td>:</td>
                            <td>{{$item2['phone']}}</td>
                        </tr>
                    </table>
                </ul>
            </div>
            <a href="/dashboard/pembayaran/pembayaran" class="mx-auto"><button class="btn bg-primary text-white">Kembali</button></a>
        </div>
    </div>
</div>
@endsection