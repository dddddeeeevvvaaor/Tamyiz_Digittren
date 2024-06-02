@extends('layout')
@section('breadcrumb')
Edit WhatsApp API
@endsection
@section('judul')
Edit WhatsApp API
@endsection
@section('content')

<div style="">
    @if(Auth::user()->role == 'admin')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit WhatsApp API</h5>
            <a href="{{ route('whatsapp_api.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('whatsapp_api.update', ['whatsapp_api' => $whatsapp_api->id_wa_api]) }}" id="whatsapp_apiForm">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="token">Token:</label>
                        <input type="text" class="form-control" id="token" name="token" value="{{ $whatsapp_api->token }}" required>
                    </div>
                    <div class="form-group">
                        <label for="base_server">Base Server:</label>
                        <input type="text" class="form-control" id="base_server" name="base_server" value="{{ $whatsapp_api->base_server }}" required>
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