@extends('layout')
@section('breadcrumb')
Edit Password User
@endsection
@section('judul')
Edit Password User
@endsection
@section('content')
@include('sweetalert::alert')


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
            <h5 class="mb-0">Edit Password User</h5>
            <a href="{{ route('calonsiswa') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('editpassworddatasiswa', ['user' => $user->id_user]) }}" id="userForm">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <!-- menampilkan email -->
                    <div class="form-group">
                        <label for="nama">Username</label>
                        <input type="text" name="username" value="{{ App\Models\User::where('id_user', $user->id_user)->first()->nama }}"class="form-control" id="username" readonly>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" name="password" value="" class="form-control" id="password" placeholder="Masukkan Password">
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