<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets2/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets2/css/pages/auth.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Untuk menyesuaikan posisi ikon mata */
        .toggle-password {
            position: absolute;
            top: 65%;
            transform: translateY(-50%);
            right: 10px;
            cursor: pointer;
        }

        /* Untuk menyesuaikan tata letak ikon mata di dalam input */
        .form-control {
            position: relative;
        }

        .form-control .toggle-password {
            z-index: 1;
            margin: 0;
            padding: 0;
        }

        /* Menyesuaikan ukuran ikon mata */
        .eye-icon {
            font-size: 1.8rem;
            /* Sesuaikan ukuran ikon mata di sini */
            color: #aaa;
        }
    </style>

    @foreach ($settingtamyiz as $settingtamyiz)
    <title>{{ $settingtamyiz->nama_pesantren }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/'.$settingtamyiz->logo) }}" type="image/x-icon">


</head>

<body>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                @include('sweetalert::alert')
                @if(session('status'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: ' {{ session('
                        status ') }}',
                    });
                </script>
                @endif

                @if(session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: ' {{ session('
                        error ') }}',
                    });
                </script>
                @endif

                @if (Session::get('notAllowed'))
                <div class="alert alert-danger">
                    {{ Session::get('notAllowed') }}
                </div>
                @endif

                <div id="auth-left">
                    <div class="auth-logo d-flex" style="align-items:center">
                        <img src="{{ asset('assets/images/logo/'.$settingtamyiz->logo) }}" alt="Logo" srcset="" style="width: 40px; height:40px">
                        <h5 style="margin-left: 15px; margin-bottom:0px">{{ $settingtamyiz->nama_pesantren }}</h5>
                    </div>
                    @endforeach
                    <h1 class="auth-title">Log in.</h1>

                    <form class="user" method="POST" action="{{ route('login.auth') }}">
                        @csrf
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (Session::get('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                        </div>
                        @endif

                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="number" name="phone" id="phone" class="form-control form-control-xl" placeholder="phone">
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" name="password" id="password" placeholder="Password">
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div class="toggle-password">
                                <i class="bi bi-eye eye-icon" id="togglePassword"></i>
                            </div>
                        </div>



                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Log in</button>
                    </form>
                    <div class="text-center mt-5 fs-5">
                        <p class="text-gray-600">Belum punya akun? </p>
                        <a href="/daftar" class="font-bold">Daftar disini.</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            // Ubah tipe input dari 'password' ke 'text' atau sebaliknya
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Ubah icon mata sesuai kondisi tampilan password
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>