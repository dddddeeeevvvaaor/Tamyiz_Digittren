@extends('layout')
@section('breadcrumb')
Setting
@endsection
@section('judul')
Setting
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
            <h5 class="mb-0">Setting</h5>
        </div>

        <!-- Form untuk search -->
        <div class="card-body">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- ./row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ isset($settingtamyiz) ? route('settingtamyiz.update', $settingtamyiz->id_settamyiz) : route('settingtamyiz.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($settingtamyiz))
                                        @method('PATCH') <!-- Menambahkan ini -->
                                        @endif
                                        <div class="form-group row">
                                            <label for="nama_pesantren" class="col-sm-2 col-form-label">Nama Pesantren</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="nama_pesantren" name="nama_pesantren" placeholder="Nama Pesantren" value="{{$settingtamyiz->nama_pesantren ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat">{{$settingtamyiz->alamat ?? ''}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="kode_pos" class="col-sm-2 col-form-label">Kode Pos</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="kode_pos" name="kode_pos" placeholder="Kode Pos" value="{{$settingtamyiz->kode_pos ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nomor_telpon" class="col-sm-2 col-form-label">Telepon</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" id="nomor_telpon" name="nomor_telpon" placeholder="Telepon" value="{{$settingtamyiz->nomor_telpon ?? ''}}" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="website" class="col-sm-2 col-form-label">Website <small><i>(opsional)</i></small></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="website" name="website" placeholder="Website" value="{{$settingtamyiz->website ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$settingtamyiz->email ?? ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="logo" class="col-sm-2 col-form-label">Logo Pesantren</label>
                                            <div class="col-sm-3">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="logo" id="customFile" accept="image/*" value="{{$settingtamyiz->logo ?? ''}}">
                                                    <label class="custom-file-label" for="customFile">{{$settingtamyiz->logo ?? ''}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <div class="checkbox">
                                                    <input type="checkbox" required> Perbarui data profil Pesantren
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>

                    </div>
                    <!-- /.row -->
                </div>
                <!--/. container-fluid -->
            </section>
        </div>
    </div>
</div>

<script>
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
@endif
</div>

@endsection