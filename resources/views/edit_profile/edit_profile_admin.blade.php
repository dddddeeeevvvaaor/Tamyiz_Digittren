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
            <h5 class="mb-0">Edit Profile</h5>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('update_profile_admin.updateProfileAdmin', $user->id_user) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required value="{{ App\Models\User::where('id_user', Auth::user()->id_user)->value('nama') }}">
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
            <h5 class="mb-0">Edit Akun</h5>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('update_akun_admin.updateAkunAdmin', $user->id_user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <div class="form-group">
                        <label for="phone">No HP:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required value="{{ $user->phone }}">
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