<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets2/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('assets2/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('assets2/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets2/css/app.css') }}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/adminlte.min.css">

    <link rel="shortcut icon" href="{{ asset('assets2/images/favicon.svg') }}" type="image/x-icon">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{config('midtrans.client_key')}}"></script>
    <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->

    <style>
        /* CSS for Dropdown Menu */
        .sidebar-item.has-submenu .submenu {
            display: none;
            list-style: none;
            padding-left: 0;
        }

        .sidebar-item.has-submenu.active .submenu {
            display: block;
        }

        /* You might need additional styling for arrow icons or animations */

        /* Gaya untuk ikon panah */
        .menu-arrow {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 5px;
            /* Menambahkan sedikit jarak dari teks */
            vertical-align: middle;
            border-top: 4px solid #444;
            /* Warna ikon panah */
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
            float: right;
            /* Menambahkan properti float: right */
        }

        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-bottom: 60px;
            /* Same height as your footer */
        }

        .page-wrapper {
            flex: 1;
        }

        .main-footer {
            background-color: #343a40;
            /* Warna latar belakang footer */
            color: #000000;
            /* Warna teks footer */
        }

        .main-footer a {
            color: #0080FF;
            /* Warna tautan footer */
        }

        .main-footer a:hover {
            color: #adb5bd;
            /* Warna tautan saat di-hover */
        }

        .main-footer {
            position: fixed;
            /* Fixed position to stick at the bottom */
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            text-align: center;
            /* Make sure it's above other elements */
        }

        /* Gaya Sidebar */
        #sidebar {
            background-color: #2c3e50;
            /* Warna latar belakang baru */
            color: #ffffff;
            /* Warna teks */
            font-family: 'Nunito', sans-serif;
            /* Font baru */
        }

        /* Gaya untuk item menu */
        .sidebar-menu .menu .sidebar-item {
            transition: background-color 0.3s;
            /* Transisi untuk efek hover */
        }

        /* Gaya untuk hover item menu */
        .sidebar-menu .menu .sidebar-item:hover {
            background-color: #FFF1F7;
            /* Warna latar saat hover */
        }


        /* Mengatur ulang gaya untuk submenu */
        .sidebar-menu .submenu {
            padding-left: 20px;
            /* Indentasi untuk submenu */
        }

        /* Gaya untuk scrollbar (jika diperlukan) */
        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #7f8c8d;
        }

        /* Gaya tambahan sesuai kebutuhan */
    </style>

    @php
    // ambil data setting tamyiz
    $settingtamyiz = App\Models\Setting_Tamyiz::first();
    @endphp
    <title>{{ $settingtamyiz->nama_pesantren ?? '' }}</title>
    <link rel="shortcut icon" href="{{ $settingtamyiz ? asset('assets/images/logo/' . $settingtamyiz->logo) : asset('path/to/default/icon') }}" type="image/x-icon">
</head>

<body class="bg-daftar" style="background-color: #B4CFDB;">
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active" style="background-color: #FFF1F7;">
                <div class="sidebar-header" style="background-color: #277855;">
                    <div class="d-flex justify-content-between">
                        @php
                        // ambil data setting tamyiz
                        $settingtamyiz = App\Models\Setting_Tamyiz::first();
                        @endphp
                        <div class="d-flex" style="align-items:center">
                            <img src="{{ $settingtamyiz ? asset('assets/images/logo/' . $settingtamyiz->logo) : asset('path/to/default/icon') }}" alt="Logo" srcset="" style="width: 40px; height:40px">
                            <h6 style="margin-left: 15px; margin-bottom:0px; color: white !important;">{{ $settingtamyiz->nama_pesantren ?? '' }}</h6>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ Request::is('dashboard')? "active":"" }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @if(Auth::user()->role == 'admin')
                        <li class="sidebar-item has-submenu">
                            <a href="#" class='sidebar-link'>
                                <!-- logo master data -->
                                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                                <span>Master Data</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="submenu" style="margin-left: 20px;"> <!-- Menambahkan margin-left untuk menjorok ke kanan -->
                                <li class="sidebar-item {{ Request::is('dashboard/settingtamyiz/index')? "active":"" }}">
                                    <a href="{{ route('settingtamyiz.index') }}" class='sidebar-link'>
                                        <i class="bi bi-gear-fill"></i>
                                        <span>Setting Tamyiz</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/golongan/index')? "active":"" }}">
                                    <a href="{{ route('golongan.index') }}" class='sidebar-link'>
                                        <i class="bi bi-people-fill"></i>
                                        <span>Golongan</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/whatsapp_api/index')? "active":"" }}">
                                    <a href="{{ route('whatsapp_api.index') }}" class='sidebar-link'>
                                        <i class="bi bi-whatsapp"></i>
                                        <span>WhatsApp API</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/tanggalbukapendaftaran/index')? "active":"" }}">
                                    <a href="{{ route('tanggalbukapendaftaran.index') }}" class='sidebar-link'>
                                        <i class="bi bi-calendar3"></i>
                                        <span>Tanggal Buka Pendaftaran</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/periode_pendaftaran/index')? "active":"" }}">
                                    <a href="{{ route('periode_pendaftaran.index') }}" class='sidebar-link'>
                                        <i class="bi bi-calendar3"></i>
                                        <span>Periode Pendaftaran</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/program/index') ? 'active' : '' }}">
                                    <a href="{{ route('program.index') }}" class='sidebar-link'>
                                        <i class="bi bi-book"></i>
                                        <span>Program</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/bank/index')? "active":"" }}">
                                    <a href="{{ route('bank.index') }}" class='sidebar-link'>
                                        <i class="bi bi-cash"></i>
                                        <span>Bank</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/pengumuman/create')? "active":"" }}">
                                    <a href="{{ route('pengumuman.create') }}" class='sidebar-link'>
                                        <i class="bi bi-megaphone"></i>
                                        <span>Pengumuman</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-submenu">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-fill"></i>
                                <span>Siswa</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="submenu" style="margin-left: 20px;"> <!-- Menambahkan margin-left untuk menjorok ke kanan -->
                                <li class="sidebar-item {{ Request::is('dashboard/data_siswa/calonsiswa') ? 'active' : '' }}">
                                    <a href="{{ route('calonsiswa') }}" class='sidebar-link'>
                                        <i class="bi bi-person-lines-fill"></i>
                                        <span>Calon siswa</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/akun_baru_user/index') ? 'active' : '' }}">
                                    <a href="{{ route('akun_baru_user.index') }}" class='sidebar-link'>
                                        <i class="bi bi-person-plus-fill"></i>
                                        <span>Akun Siswa Diterima</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="sidebar-item has-submenu">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-circle"></i>
                                <span>Keuangan</span>
                                <span class="menu-arrow caret"></span> <!-- Menambahkan kelas .caret -->
                            </a>
                            <ul class="submenu" style="margin-left: 20px;"> <!-- Menambahkan margin-left untuk menjorok ke kanan -->
                                <li class="sidebar-item {{ Request::is('dashboard/pembayaran/pembayaran')? "active":"" }}">
                                    <a href="{{ route('pembayaran') }}" class='sidebar-link'>
                                        <i class="bi bi-cash"></i>
                                        <span>Pembayaran</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/pembayaran/pembayaran_diterima')? "active":"" }}">
                                    <a href="{{ route('pembayaran_diterima') }}" class='sidebar-link'>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Pembayaran Di Diterima</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/pembayaran/pembayaran_ditolak')? "active":"" }}">
                                    <a href="{{ route('pembayaran_di_tolak') }}" class='sidebar-link'>
                                        <i class="bi bi-x-circle-fill"></i>
                                        <span>Pembayaran Di Tolak</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ Request::is('dashboard/laporanakuntansi/akuntansi')? "active":"" }}">
                                    <a href="{{ route('akuntansi.index') }}" class='sidebar-link'>
                                        <i class="bi bi-book"></i>
                                        <span>Data Keuangan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @elseif(Auth::user()->role == 'student')
                        <li class="sidebar-item {{ Request::is('dashboard/pembayaran/pembayaran')? "active":"" }}">
                            <a href="{{ route('pembayaran') }}" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Pembayaran</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ Request::is('dashboard/pembayaran/pembayaran_infaq')? "active":"" }}">
                            <a href="{{ route('pembayaran_infaq') }}" class='sidebar-link'>
                                <i class="bi bi-cash"></i>
                                <span>Pembayaran Infaq</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main" class='layout-navbar' style="background-color: #B4CFDB;">
            <header class='mb-3'>
                <nav class="main-header navbar navbar-expand bg-danger navbar-light border-bottom" style="background-color: #277855 !important;">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3" style="color: white !important;"></i>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <!-- membuat style warna pada teks -->
                                            <h6 class="mb-0 text-gray-600" style="color: white !important;">{{ Auth::user()->nama }}</h6>
                                            @if(Auth::check())
                                            @if(Auth::user()->role == 'student')
                                            @php
                                            // ambil data siswa berdasarkan nama
                                            $siswa = App\Models\Siswa::where('id_user', Auth::user()->id_user)->first();
                                            @endphp
                                            @if($siswa->jenis_kelamin == 'L')
                                            <p class="mb-0 text-sm text-gray-600" style="color: white !important;">Santriwan</p>
                                            @elseif($siswa->jenis_kelamin == 'P')
                                            <p class="mb-0 text-sm text-gray-600" style="color: white !important;">Santriwati</p>
                                            @endif
                                            @else
                                            <p class="mb-0 text-sm text-gray-600" style="color: white !important;">{{ Auth::user()->role }}</p>
                                            @endif
                                            @endif
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <!-- menampilkan foto profil sesuai dengan jenis_kelamin -->
                                                @if(Auth::check())
                                                @if(Auth::user()->role == 'student')
                                                @php
                                                // ambil data siswa berdasarkan nama
                                                $siswa = App\Models\Siswa::where('id_user', Auth::user()->id_user)->first();
                                                @endphp
                                                @if($siswa->jenis_kelamin == 'L')
                                                <img src="{{ asset('assets/images/laki-laki/santriwan.jpg') }}" alt="profile-user" class="img-circle elevation-2" style="width: 40px; height: 40px;margin-top: -8px;margin-right: -5px;border-radius: 100%;">
                                                @elseif($siswa->jenis_kelamin == 'P')
                                                <img src="{{ asset('assets/images/perempuan/santriwati.jpg') }}" alt="profile-user" class="img-circle elevation-2" style="width: 40px; height: 40px;margin-top: -8px;margin-right: -5px;border-radius: 100%;">
                                                @endif
                                                @else
                                                <img src="{{asset('assets/images/admin/admin.jpg')}}" alt="profile-user" class="img-circle elevation-2" style="width: 40px; height: 40px;margin-top: -8px;margin-right: -5px;border-radius: 100%;">
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <h6 class="dropdown-header">Hello, {{ Auth::user()->nama }}!</h6>
                                    </li>
                                    @if(Auth::user()->role == 'admin')
                                    <li><a class="dropdown-item" href="/edit_profile_admin/{{ Auth::user()->id_user }}"><i class="icon-mid bi bi-person me-2"></i> Edit Profile</a></li>
                                    @elseif(Auth::user()->role == 'student')
                                    <li><a class="dropdown-item" href="/edit_profile_siswa/{{ Auth::user()->id_user }}"><i class="icon-mid bi bi-person me-2"></i> Edit Profile</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="/logout"><i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <div id="main-content">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-8 order-md-1 order-last">
                            <h3>@yield('judul')</h3>
                        </div>
                        <div class="col-12 col-md-4 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Responsive tables start -->
                <section class="section">
                    <div class="row" id="table-responsive">
                        <div class="col-12">
                            <div class="card">

                                @yield('content')

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <footer class="main-footer d-none d-md-block ">
        <div class="float-right">
            Website : <a data-toggle="tooltip" target="_blank" href="{{ $settingtamyiz->website ?? '' }}">{{ $settingtamyiz->website ?? '' }}</a>
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a data-toggle="tooltip" target="_blank">{{ $settingtamyiz->nama_pesantren ?? ''}}</a>.</strong> All rights reserved.
    </footer>

    <script src="{{ asset('assets2/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets2/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets2/js/main.js') }}"></script>

    <script>
        // jQuery to toggle submenu visibility
        $('.sidebar-item.has-submenu > .sidebar-link').click(function(e) {
            e.preventDefault();
            const $submenu = $(this).siblings('.submenu');
            $('.sidebar-item.has-submenu').not($(this).parent()).removeClass('active').find('.submenu').slideUp();
            $(this).parent().addClass('active'); // Menambahkan kelas .active ke elemen .sidebar-item.has-submenu
            $(this).parent().toggleClass('active').find('.submenu').slideToggle();
        });
    </script>

    <!-- REQUIRED SCRIPTS -->
    <!-- pace-progress -->
    <script src="/assets3/plugins/pace-progress/pace.min.js"></script>
    <!-- jQuery -->
    <script src="/assets3/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/assets3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/assets3/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets3/dist/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="/assets3/dist/js/demo.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="/assets3/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="/assets3/plugins/raphael/raphael.min.js"></script>
    <script src="/assets3/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="/assets3/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <!-- ChartJS -->
    <script src="/assets3/plugins/chart.js/Chart.min.js"></script>

    <!-- Select2 -->
    <script src="/assets3/plugins/select2/js/select2.full.min.js"></script>

    <!-- Bootstrap4 Duallistbox -->
    <script src="/assets3/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

    <!-- DataTables -->
    <script src="/assets3/plugins/datatables/jquery.dataTables.js"></script>
    <script src="/assets3/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



    <!-- bs-custom-file-input -->
    <script src="/assets3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>

    <script type="text/javascript">
        function CheckPendaftaran(val) {
            var element = document.getElementById('kelas');
            var element1 = document.getElementById('kelas_bawah');
            var element2 = document.getElementById('kelas_all');
            if (val == '1')
                element.style.display = 'none',
                element.required = '',
                element1.style.display = 'block',
                element1.required = 'required',
                element1.name = 'kelas_id',
                element2.style.display = 'none',
                element2.required = '',
                element2.name = '',
                element2.value = '';
            else
                element2.style.display = 'block',
                element2.required = 'required',
                element2.name = 'kelas_id',
                element.style.display = 'none',
                element.required = '',
                element1.style.display = 'none',
                element1.required = '',
                element1.name = '',
                element1.value = '';


        }
    </script>

    <!-- PAGE SCRIPTS -->
    <script src="/assets3/dist/js/pages/dashboard2.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
            $('#example3').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });

            $('#example4').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()
        });
    </script>

</body>

</html>