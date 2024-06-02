@extends('layout')
@section('breadcrumb')
Akun Baru Santri
@endsection
@section('judul')
Akun Baru Santri
@endsection
@section('content')
@include('sweetalert::alert')

<style>
    /* Atur lebar maksimum dropdown menu */
    .dropdown-menu {
        max-width: 200px;
        /* Sesuaikan nilai ini sesuai kebutuhan */
    }

    /* Opsi tambahan: Atur agar teks tidak terpotong */
    .dropdown-item {
        white-space: normal;
        /* Memastikan teks tidak terpotong */
    }
</style>

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
            <h5 class="mb-0">Akun Baru Santri</h5>
            <!-- Card tools untuk aksi seperti import dan export -->
            <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-toggle="modal" data-target="#modal-import">
                    <i class="fas fa-upload"></i> Import
                </button>
                <a href="{{ route('akun_baru_user.export', ['status' => request('status') ?? 'all', 'golongan' => request('golongan') ?? 'all']) }}" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i> Export
                </a>

            </div>
            <!-- Modal import  -->
            <div class="modal fade" id="modal-import">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Import Data Akuntansi</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form name="contact-form" action="{{ route('akun_baru_user.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="callout callout-info">
                                    <h5>Download format import</h5>
                                    <p>Silahkan download file format import melalui tombol dibawah ini.</p>
                                    <a href="{{ route('akun_baru_user.format_import') }}" class="btn btn-primary text-white" style="text-decoration:none"><i class="fas fa-file-download"></i> Download</a>
                                </div>
                                <div class="form-group row pt-2">
                                    <label for="file_import" class="col-sm-2 col-form-label">File Import</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="file_import" id="customFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <label class="custom-file-label" for="customFile">Pilih file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal import -->
        </div>
        <!-- Filter Status dan Golongan bersebelahan -->
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Role</th>
                            <th scope="col">Golongan</th>
                            <th scope="col">Paket</th>
                            <th scope="col">Tanggal Mulai</th>
                            <th scope="col">Tanggal Berakhir</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach($akun_baru_user as $akun)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $akun->firstname }}</td>
                            <td>{{ $akun->lastname }}</td>
                            <td>{{ $akun->username }}</td>
                            <td>{{ $akun->email }}</td>
                            <td>{{ $akun->role }}</td>
                            @foreach($golongan as $gol)
                            @if($akun->id_golongan == $gol->id_golongan)
                            <td>{{ $gol->nama_golongan }}</td>
                            @endif
                            @endforeach
                            @foreach($payments as $payment)
                            @endforeach
                            <td>
                                @php
                                if ($akun->payment->jumlah_pembayaran % 12 == 0) {
                                echo ($akun->payment->jumlah_pembayaran / 12) . ' tahun';
                                } else {
                                echo $akun->payment->jumlah_pembayaran . ' bulan';
                                }
                                @endphp
                            </td>
                            <td>{{ $akun->mulai }}</td>
                            <td>{{ $akun->berakhir }}</td>
                            <td>
                                <form action="{{ route('update_status', $akun->id_newuser) }}" method="POST" class="status-form" data-user-id="{{ $akun->id_newuser }}" data-current-status="{{ $akun->status }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $akun->status }}">
                                    <button type="submit" class="btn btn-{{ $akun->status === 'active' ? 'success' : 'danger' }}" data-toggle="status" data-user-id="{{ $akun->id_newuser }}" data-current-status="{{ $akun->status }}">
                                        {{ $akun->status === 'active' ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <!-- <a href="{{ route('akun_baru_user.editpassword', ['akun_baru_user' => $akun->id_newuser]) }}" class="btn btn-sm btn-primary mx-1">Edit</a> -->
                                    @if($akun->status === 'inactive')
                                    <!-- Tombol Pembayaran Ulang -->
                                    <form action="{{ route('reopen_and_delete', $akun->id_newuser) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda ingin melakukan pembayaran lagi? Pembayaran sebelumnya akan dihapus.')">Extend</button>
                                    </form>
                                    @endif
                                    <form action="{{ route('akun_baru_user.destroy', ['akun_baru_user' => $akun->id_newuser]) }}" method="POST">
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-size">
                        <select class="form-select form-select-sm" id="jumlah" onchange="changePerPage()">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            <option value="all" {{ $perPage == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                    <!-- Tempatkan komponen lain untuk navigasi pagination di sini jika diperlukan -->
                    <div class="pagination-total">
                        @if ($akun_baru_user instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <p>Menampilkan {{ $akun_baru_user->firstItem() }} - {{ $akun_baru_user->lastItem() }} dari {{ $akun_baru_user->total() }} data</p>
                        @endif
                    </div>
                    {{-- Pagination Links --}}
                    @if ($akun_baru_user->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($akun_baru_user->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $akun_baru_user->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                            @endif

                            {{-- Pagination Number --}}
                            <li class="page-item">
                                <div class="page-link">{{ $akun_baru_user->currentPage() }} dari {{ $akun_baru_user->lastPage() }}</div>
                            </li>

                            {{-- Next Page Link --}}
                            @if ($akun_baru_user->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $akun_baru_user->nextPageUrl() }}" rel="next">Next</a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@else
@endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function changePerPage() {
        var perPage = document.getElementById('jumlah').value;
        window.location.href = `{{ url('/dashboard/akun_baru_user/index?perPage=${perPage}') }}`;
    }

    $(document).ready(function() {
        $('button[data-toggle="status"]').click(function(e) {
            e.preventDefault(); // Hindari aksi bawaan tombol (submit form)

            var userId = $(this).data('user-id');
            var currentStatus = $(this).data('current-status');

            // Kirim permintaan ke server untuk memperbarui status
            $.ajax({
                type: 'PATCH',
                url: '/update_status/' + userId,
                data: {
                    status: currentStatus === 'active' ? 'inactive' : 'active',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Jika berhasil, perbarui tampilan tombol sesuai dengan status yang baru
                    if (response.success) {
                        var newStatus = response.new_status;
                        $('button[data-toggle="status"][data-user-id="' + userId + '"]').data('current-status', newStatus);
                        $('button[data-toggle="status"][data-user-id="' + userId + '"]').removeClass('btn-success btn-danger');

                        if (newStatus === 'active') {
                            $('button[data-toggle="status"][data-user-id="' + userId + '"]').addClass('btn-success').text('Active');
                        } else {
                            $('button[data-toggle="status"][data-user-id="' + userId + '"]').addClass('btn-danger').text('Inactive');
                        }
                    }
                },
                error: function(error) {
                    // Handle error jika terjadi kesalahan
                    console.error('Error:', error);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');

        // Event listener untuk filter status
        statusFilter.addEventListener('change', function() {
            const selectedStatus = statusFilter.value;

            // Redirect ke halaman dengan status yang dipilih
            window.location.href = "{{ route('akun_baru_user.index') }}" + (selectedStatus ? `?status=${selectedStatus}` : '');
        });
    });
</script>
@endsection