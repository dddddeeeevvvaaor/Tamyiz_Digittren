@extends('layout')
@section('breadcrumb')
Edit Password
@endsection
@section('judul')
Edit Password
@endsection
@section('content')

<div style="">
    @if(Auth::user()->role == 'admin')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Password</h5>
            <a href="{{ route('akun_baru_user.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('akun_baru_user.updatepassword', ['akun_baru_user' => $akun_baru_user->id]) }}" id="akun_baru_userForm">
                    @csrf
                    @method('PATCH')
                    <!-- Isi form -->
                    <!-- menampilkan email -->
                    <div class="form-group">
                        <label for="email">Username</label>
                        <input type="text" name="username" value="{{ $akun_baru_user->username }}" class="form-control" id="username" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="{{ $akun_baru_user->email }}" class="form-control" id="email" readonly>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" value="" class="form-control" id="password" placeholder="Masukkan Password">
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