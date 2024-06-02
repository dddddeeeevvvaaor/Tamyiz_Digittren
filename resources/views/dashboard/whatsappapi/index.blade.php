@extends('layout')
@section('breadcrumb')
WhatsApp API
@endsection
@section('judul')
WhatsApp API
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
            <h5 class="mb-0">Daftar WhatsApp API</h5>
            <!-- Tambah button -->
            @if(count($whatsapp_api) > 0)
            <!-- Jika ada data, tombol Tambah disembunyikan -->
            @else
            <a href="{{ route('whatsapp_api.create') }}" class="btn btn-primary">Tambah</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Token</th>
                            <th>Base Server</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($whatsapp_api as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->token }}</td>
                            <td>{{ $item->base_server }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('whatsapp_api.edit', ['whatsapp_api' => $item->id_wa_api]) }}" class="btn btn-sm btn-primary mx-1">Edit</a>
                                    <form action="{{ route('whatsapp_api.destroy', ['whatsapp_api' => $item->id_wa_api]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
@endif
</div>

@endsection