@extends('layout')
@section('judul')
Selamat Datang {{ Auth::user()->nama }}
@endsection
@section('content')

<style>
    .product-img {
        position: relative;
        /* Menetapkan posisi relatif ke kontainer gambar */
        display: inline-block;
        /* Agar kontainer dapat berfungsi dengan position:relative */
    }

    .status-icon {
        width: 20px;
        /* Atur ukuran ikon */
        height: 20px;
        /* Atur ukuran ikon */
        border-radius: 50%;
        /* Membuat bentuk bulat */
        position: absolute;
        /* Posisi absolut untuk ikon */
        bottom: 0;
        /* Letakkan ikon di bawah kontainer */
        right: 0;
        /* Letakkan ikon di kanan kontainer */
        background-color: green;
        /* Warna default untuk status online */
        border: 2px solid white;
        /* Menambahkan border putih untuk memisahkan dari gambar */
    }

    .offline {
        background-color: grey;
        /* Warna untuk status offline */
    }
</style>

@if (Session::get('notAllowed'))
<div class="alert alert-danger">
    {{ Session::get('notAllowed') }}
</div>
@endif

@if(Auth::user()->role == 'admin')
<div class="content-wrapper" style="padding: 15px 15px 15px 15px; background-color: #F7F6F6;">
    <div class="content-header">
        <div class="container-fluid" style="border-radius: 15px;">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="row">
        <div class="col-md-9">
            <section class="content card" style="padding: 15px 15px 40px 15px ">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h4><i class="nav-icon fas fa-warehouse my-0 btn-sm-1"></i> Rekap Data Pesantren</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Small boxes (Stat box) -->
                        <div class="filter-container p-0 row d-flex justify-content-center">
                            <div class="col-lg-3 col-md-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{$data_jumlah_siswa_atau_pendaftar}}</h3>
                                        <p>Pendaftar</p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-user-plus"></i>
                                    </div>
                                    <p class="small-box-footer">Jumlah Pendaftar</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{$data_jumlah_siswa_diterima}}</h3>
                                        <p>Santri </p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-user-graduate"></i>
                                    </div>
                                    <p class="small-box-footer">Jumlah Santri</p>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-md-6">
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{$data_akun_active}}</h3>
                                        <p>Akun Aktive</p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-user-check"></i>
                                    </div>
                                    <p class="small-box-footer">Jumlah Akun Aktive</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{$data_akun_unactive}}</h3>
                                        <p>Akun inactive</p>
                                    </div>
                                    <div class="icon">
                                        <i class="nav-icon fas fa-user-times"></i>
                                    </div>
                                    <p class="small-box-footer">Jumlah Akun inactive</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="content card" style="padding: 15px 15px 0px 15px ">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h4><i class="nav-icon fas fa-dollar-sign my-0 btn-sm-1"></i> Keuangan Tamyiz</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-1 pr-1">
                            <a href="javascript:void(0)" class="product-title">Jumlah Pemasukan</a>
                            <h5> Rp. {{ number_format($data_debet,0,',','.') }}</h5>
                            <hr>
                        </ul>
                        <ul class="products-list product-list-in-card pl-1 pr-1">
                            <a href="javascript:void(0)" class="product-title">Saldo</a>
                            <h5> Rp. {{ number_format($data_jumlah_saldo,0,',','.') }}</h5>
                            <hr />
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <section class="content card" style="padding: 10px 10px 10px 10px ">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h4><i class="nav-icon fas fa-bullhorn my-0 btn-sm-1"></i> Pengumuman</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->
                        <div class="timeline timeline-inverse">
                            <!-- timeline time label -->
                            <div class="time-label">
                                <span class="bg-success">
                                    Pengumuman Terakhir
                                </span>
                            </div>
                            @foreach($data_pengumuman->sortByDesc('created_at') as $pengumuman)
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-envelope bg-primary"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-calendar-alt"></i> {{$pengumuman->created_at}} <br> {{$pengumuman->created_at->diffForHumans()}} </span>
                                    <h3 class="timeline-header"><a class="text-primary">{{$pengumuman->user->role}}</a><br> {{$pengumuman->judul}}</h3>
                                    <div class="timeline-body">
                                        {!! $pengumuman->isi !!}
                                    </div>
                                </div>
                            </div>
                            <!-- END timeline item -->
                            @endforeach
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="content card" style="padding: 10px 10px 10px 10px ">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h4><i class="nav-icon fas fa-headset my-0 btn-sm-1"></i> Team Teknis</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($team_teknis as $item)
                            @if($item->id_user == 1 || $item->id_user == 2)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{ asset('adminlte/img/support.png') }}" alt="Product Image" class="img-size-50">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">{{App\Models\User::where('id_user', $item->id_user)->first()->nama}}
                                        <span class="badge badge-warning float-right">Administrator</span></a>
                                    <span class="product-description text-info">
                                        HP : {{$item['phone']}}
                                    </span>
                                </div>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="content card" style="padding: 10px 10px 10px 10px ">
                <div class="box">
                    <div class="row">
                        <div class="col">
                            <h4><i class="nav-icon fas fa-user-tag my-0 btn-sm-1"></i> Status Login</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @foreach($data_login as $item)
                            <li class="item">
                                <div class="product-img position-relative">
                                    <img src="{{ asset('adminlte/img/user.png') }}" alt="Product Image" class="img-size-50">
                                    <!-- Ikon status online/offline -->
                                    <span class="status-icon {{ $item->is_online ? 'online' : 'offline' }}"></span>
                                </div>
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">{{$item->nama}}</a>
                                    <span class="time"><i class="far fa-clock"></i> {{$item->updated_at->diffForHumans()}}</span></a>
                                    <span class="product-description">
                                        {{$item->nist}}
                                    </span>
                                </div>
                            </li>
                            @endforeach
                            <!-- /.item -->
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
    </section>


    @else

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="row">
            <div class="col-md-9">
                <section class="content card" style="padding: 15px 15px 40px 15px ">
                    <div class="card-header">
                        <div class="col">
                            <h2><i class="nav-icon fas fa-user my-0 btn-sm-1"></i> Berikut informasi mengenai profil anda</h2>
                            <hr>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($siswa as $item)
                        <table cellspacing="0" cellpadding="4">
                            <tr>
                                <th width="50%">NIST</th>
                                <td>:</td>
                                <td>{{ $item->nist }}</td>
                            </tr>
                            <tr>
                                <th width="50%">Nama</th>
                                <td>:</td>
                                <td>{{ $item->user->nama }}</td>
                            </tr>
                            @foreach ($user as $item2)
                            @endforeach
                            <tr>
                                <th width="50%">Nomor Hp</th>
                                <td>:</td>
                                <td>{{ $item2['phone'] }}</td>
                            </tr>
                        </table>
                        @endforeach
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section class="content card" style="padding: 150px 5px 0px 0px">
                    <!-- tempat untuk menampilkan foto siswa -->
                    <div class="box">
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-1 pr-1">
                                <div class="text-center">
                                    @if(Auth::check())
                                    @if(Auth::user()->role == 'student')
                                    @php
                                    // ambil data siswa berdasarkan nama
                                    $siswa = App\Models\Siswa::where('id_user', Auth::user()->id_user)->first();
                                    @endphp
                                    @if($siswa->jenis_kelamin == 'L')
                                    <img src="{{ asset('assets/images/laki-laki/santriwan.jpg') }}" alt="Foto siswa" class="img-square" style="width: 200px; height: 200px;">
                                    @elseif($siswa->jenis_kelamin == 'P')
                                    <img src="{{ asset('assets/images/perempuan/santriwati.jpg') }}" alt="Foto siswa" class="img-square" style="width: 200px; height: 200px;">
                                    @endif
                                    @else
                                    <img src="{{asset('foto/'.Auth::user()->foto)}}" alt="Foto siswa" class="img-square" style="width: 200px; height: 200px;">
                                    @endif
                                    @endif
                                </div>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
            <div class="row">
                <div class="col-md-100">
                    <section class="content card" style="padding: 15px 15px 40px 15px">
                        <div class="card-header">
                            <div class="col">
                                <!-- informasi pembayaran -->
                                <h2><i class="nav-icon fas fa-money-check-alt my-0 btn-sm-1"></i> Informasi Pembayaran</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @forelse($payment as $singlePayment)
                                <div class="col-md-6">
                                    <!-- Informasi Detail Pembayaran -->
                                    <h4>Detail Pembayaran</h4>
                                    <p>Bank: {{ $singlePayment->nama_bank }}</p>
                                    <p>Pemilik Rekening: {{ $singlePayment->pemilik_rekening }}</p>
                                    <p>Nominal: Rp. {{ number_format($singlePayment->nominal,0,',','.') }}</p>
                                    <p style="display: inline-block; margin-right: 10px;">Status:</p>
                                    @if($singlePayment->status == 0)
                                    <!-- membuat seperti box untuk status pembayaran -->
                                    <div class="alert alert-warning" style="padding: 5px; display: inline-block;">
                                        Belum diverifikasi
                                    </div>
                                    @elseif($singlePayment->status == 1)
                                    <div class="alert alert-success" style="padding: 5px; display: inline-block;">
                                        Sudah diverifikasi
                                    </div>
                                    @elseif($singlePayment->status == 2)
                                    <div class="alert alert-danger" style="padding: 5px; display: inline-block;">
                                        Pembayaran ditolak
                                    </div>
                                    @else
                                    <div class="alert alert-danger" style="padding: 5px; display: inline-block;">
                                        Status tidak valid
                                    </div>
                                    @endif
                                </div>
                                @empty
                                <div class="col-md-12">
                                    <p>Tidak ada data pembayaran.</p>
                                </div>
                                @endforelse
                            </div>
                            <!-- membuat tombol untuk menuju halaman pembayaran dibuat di tengah -->
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    @php
                                    $showButton = true;
                                    foreach ($payment as $singlePayment) {
                                    if ($singlePayment->status == 0 || $singlePayment->status == 1) {
                                    $showButton = false;
                                    break;
                                    }
                                    }
                                    @endphp

                                    @if ($showButton)
                                    <a href="{{ route('pembayaran') }}" class="btn btn-primary">Buat Pembayaran</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="col-md-6">
                <section class="content card" style="padding: 10px 10px 10px 10px ">
                    <div class="box">
                        <div class="row">
                            <div class="col">
                                <h4><i class="nav-icon fas fa-bullhorn my-0 btn-sm-1"></i> Pengumuman</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <div class="timeline timeline-inverse">
                                <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success">
                                        Pengumuman Terakhir
                                    </span>
                                </div>
                                @foreach($data_pengumuman->sortByDesc('created_at') as $pengumuman)
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-envelope bg-primary"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-calendar-alt"></i> {{$pengumuman->created_at}} <br> {{$pengumuman->created_at->diffForHumans()}} </span>
                                        <h3 class="timeline-header"><a class="text-primary">{{$pengumuman->user->role}}</a><br> {{$pengumuman->judul}}</h3>
                                        <div class="timeline-body">
                                            {!! $pengumuman->isi !!}
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                @endforeach
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section class="content card" style="padding: 10px 10px 10px 10px ">
                    <div class="box">
                        <div class="row">
                            <div class="col">
                                <h4><i class="nav-icon fas fa-headset my-0 btn-sm-1"></i> Team Teknis</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @foreach($team_teknis as $item)
                                @if($item->id_user == 1 || $item->id_user == 2)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ asset('adminlte/img/support.png') }}" alt="Product Image" class="img-size-50">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{$item->nama}}
                                            <span class="badge badge-warning float-right">Administrator</span></a>
                                        <span class="product-description text-info">
                                            HP : {{$item['phone']}}
                                        </span>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section class="content card" style="padding: 10px 10px 10px 10px ">
                    <div class="box">
                        <div class="row">
                            <div class="col">
                                <h4><i class="nav-icon fas fa-user-tag my-0 btn-sm-1"></i> Riwayat Login</h4>
                                <hr>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                @foreach($data_login as $item)
                                <li class="item">
                                    <div class="product-img position-relative">
                                        <img src="{{ asset('adminlte/img/user.png') }}" alt="Product Image" class="img-size-50">
                                        <!-- Ikon status online/offline -->
                                        <span class="status-icon {{ $item->is_online ? 'online' : 'offline' }}"></span>
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{$item->nama}}</a>
                                        <span class="time"><i class="far fa-clock"></i> {{$item->updated_at->diffForHumans()}}</span></a>

                                        <span class="product-description">
                                            {{$item->nist}}
                                        </span>
                                    </div>
                                </li>
                                @endforeach
                                <!-- /.item -->
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @endif

        @endsection