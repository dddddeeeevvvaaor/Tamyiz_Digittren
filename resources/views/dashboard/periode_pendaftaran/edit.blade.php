@extends('layout')
@section('breadcrumb')
Edit Periode Pendaftaran
@endsection
@section('judul')
Edit Periode Pendaftaran
@endsection
@section('content')

<style>
    .input-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-control,
    .custom-select {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: .375rem .75rem;
        height: calc(2.25rem + 2px);
        /* Adjust the height to match your input field */
    }

    .form-control:focus,
    .custom-select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    /* Adjust the appearance of the dropdown button */
    .custom-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: url('data:image/png;base64,...') no-repeat right .75rem center;
        /* Insert a base64 encoded image of a dropdown arrow */
        background-size: 12px;
        /* Adjust the size of the dropdown arrow */
    }

    /* Ensure the dropdown arrow does not overlap the text */
    .custom-select:not([multiple]) {
        padding-right: 2.5rem;
    }
</style>


<div style="">
    @if(Auth::user()->role == 'admin')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Periode Pendaftaran</h5>
            <a href="{{ route('periode_pendaftaran.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form method="POST" action="{{ route('periode_pendaftaran.update', ['periode' => $periode->id_periodedaftar]) }}" id="periode_pendaftaranForm">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <div class="input-group">
                            <!-- Input asli untuk pengguna -->
                            <input type="text" class="form-control @error('periode') is-invalid @enderror" id="periode" name="periode_display" value="{{ $periode->periode }}" placeholder="Masukkan Periode">

                            <!-- Input tersembunyi untuk menyimpan nilai yang dikirim ke server -->
                            <input type="hidden" id="periode_value" name="periode" value="{{ old('periode') }}">
                            <div class="input-group-append">
                                <select class="custom-select" id="periodeType" name="periodeType">
                                    <option selected>Pilih...</option>
                                    <option value="bulan">Bulan</option>
                                    <option value="tahun">Tahun</option>
                                </select>
                            </div>
                            @error('periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="diskon">Diskon</label>
                        <input type="text" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon" value="{{ $periode->diskon }}" placeholder="Masukkan Diskon">
                        @error('diskon')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var periodeType = document.getElementById('periodeType');
            var periodeDisplay = document.getElementById('periode');
            var periodeValue = document.getElementById('periode_value');

            function updatePeriodeValue() {
                var type = periodeType.value;
                var displayValue = periodeDisplay.value;

                if (!isNaN(displayValue) && displayValue.trim() !== '') {
                    var numericValue = parseInt(displayValue, 10); // Konversi ke integer

                    if (type === 'tahun') {
                        periodeValue.value = numericValue * 12;
                    } else if (type === 'bulan') {
                        periodeValue.value = numericValue;
                    }
                } else {
                    periodeValue.value = '';
                }
            }

            periodeDisplay.addEventListener('input', updatePeriodeValue);
            periodeType.addEventListener('change', updatePeriodeValue);
        });
    </script>
    @else
    @endif
</div>
@endsection