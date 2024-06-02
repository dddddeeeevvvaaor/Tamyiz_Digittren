@extends('layout')
@section('breadcrumb')
Tambah WhatsApp API
@endsection
@section('judul')
Tambah WhatsApp API
@endsection
@section('content')

<div style="">
    @if(Auth::user()->role == 'admin')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah WhatsApp API</h5>
            <a href="{{ route('whatsapp_api.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('whatsapp_api.store') }}" id="whatsapp_apiForm">
                    @csrf
                    <div class="form-group">
                        <label for="token">Token:</label>
                        <input type="text" class="form-control" id="token" name="token" required>
                    </div>
                    <div class="form-group">
                        <label for="base_server">Base Server:</label>
                        <input type="text" class="form-control" id="base_server" name="base_server" required>
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