@extends('layout')
@section('breadcrumb')
Edit Profile

@endsection
@section('judul')
Profile
@endsection
@section('content')
@include('sweetalert::alert')

<div class="container-fluid">
    @if(Auth::user()->role == 'student')
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
            <h5 class="mb-0">Edit Profile</h5>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('update_profile_siswa.updateProfileSiswa', $user->id_user) }}">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    @foreach($siswa as $siswa)
                    @endforeach
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" readonly value="{{ $siswa->user->nama }}">
                    </div>
                    <div class="form-group">
                        <label for="nist">NIST:</label>
                        <input type="text" class="form-control" id="nist" name="nist" readonly value="{{ $siswa->nist }}">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir:</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required value="{{ $siswa->tanggal_lahir }}">
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin:</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L" @if($siswa->jenis_kelamin == 'L') selected @endif>Laki-laki</option>
                            <option value="P" @if($siswa->jenis_kelamin == 'p') selected @endif>Perempuan</option>
                        </select>
                    </div>
                    <!-- membuat edit city -->
                    <div class="form-group">
                        <label for="city">Kota:</label>
                        <input type="text" class="form-control" id="city" name="city" required value="{{ $siswa->city }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

            </div>
        </div>
    </div>

    @else
    @endif
</div>
<hr style="height:20px; color:blue">
<div class="container-fluid">
    @if(Auth::user()->role == 'student')
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
            <h5 class="mb-0">Edit Akun</h5>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('update_akun_siswa.updateAkunSiswa', $user->id_user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <div class="form-group">
                        <label for="phone">No HP:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="text" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

            </div>
        </div>
    </div>
    @else
    @endif
</div>

@endsection