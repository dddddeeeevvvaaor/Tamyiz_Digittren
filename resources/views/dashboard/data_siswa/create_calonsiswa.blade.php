@extends('layout')
@section('breadcrumb')
Tambah Calon Santri
@endsection
@section('judul')
Tambah Calon Santri
@endsection
@section('content')
@include('sweetalert::alert')

<style>
    .required-field::before {
        content: "*";
        color: red;
    }
</style>

<div style="">
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Calon Santri</h5>
            <a href="{{ route('calonsiswa') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('pendaftaran_yang_dilakukan_admin') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <label for="tanggal_lahir" class="mb-2">Tanggal lahir <span class="required-field text-danger"></span></label>
                        <div class="form-group col-4">
                            <select name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                                <option value="" disabled selected>Tanggal</option>
                                <!-- Generate options for date -->
                                @for ($i = 1; $i <= 31; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <select name="bulan_lahir" id="bulan_lahir" class="form-control" required>
                                <option value="" disabled selected>Bulan</option>
                                <!-- Membuat pilihan bulan -->
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <select name="tahun_lahir" id="tahun_lahir" class="form-control" required>
                                <option value="" disabled selected>Tahun</option>
                                <!-- Generate options for year -->
                                @for ($i = date('Y'); $i >= 1900; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Column 1 --}}
                        <div class="col-md-6">
                            {{-- Form groups for the left column --}}
                            <div class="form-group">
                                <label for="nama" class="mb-2">Nama <span class="required-field text-danger"></span></label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap" value="" required autocomplete="nama">
                            </div>
                            <div class="form-group">
                                <label for="telp" class="mb-2">Nomor WhatsApp <span class="required-field text-danger"></span></label>
                                <input type="number" name="phone" id="telp" class="form-control" placeholder="Contoh : 08--------" required>
                            </div>
                            {{-- ... other form groups for the left column ... --}}
                        </div>

                        {{-- Column 2 --}}
                        <div class="col-md-6">
                            {{-- Form groups for the right column --}}
                            <div class="form-group">
                                <label for="jenis_kelamin" class="ml-2 mb-2">Jenis Kelamin <span class="required-field text-danger"></span></label>
                                <select name="jenis_kelamin" class="form-control w-100" id="jenis_kelamin" required>
                                    <option value="" hidden>--Jenis Kelamin--</option>
                                    <option value="P">Perempuan</option>
                                    <option value="L">Laki-Laki</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_program" class="ml-2 mb-2">Jenis Program <span class="required-field text-danger"></span></label>
                                <select name="id_program" class="form-control w-100" id="id_program" required>
                                    <option value="" hidden>--Jenis Program--</option>
                                    <!-- Loop through programs to populate options -->
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id_program }}">{{ $program->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="mb-2">Kota <span class="required-field text-danger"></span></label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="Masukkan Kota" value="" required autocomplete="city">
                        </div>
                        <div class="d-flex justify-content-center">
                            <div></div>
                            <button type="submit" id="submitBtn" class="btn btn-main-sm shadow-md bordered mt-3" style="background-color: #1c2558;"><span style="color:white;">Registrasi</span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    @endif

    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function() {
                $(this).prop('disabled', true); // Disable the button
                $(this).html('<span style="color:white;">Loading...</span>'); // Change button text to "Loading..."
                // Submit the form
                $(this).closest('form').submit();
            });
        });
    </script>

</div>
@endsection