<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akademik Tamyiz Online</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous" />
    <!-- CSS untuk Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Tema Select2 Bootstrap4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <!-- cdn bootstrap4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .required-field::before {
            content: "*";
            color: red;
        }
    </style>

</head>

@foreach ($settingtamyiz as $settingtamyiz)

<body class="bg-daftar">
    <div class="d-flex justify-content-center">@include('sweetalert::alert')
        @if(session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("status") }}',
            });
        </script>
        @endif

        <!-- SweetAlert for Error Message -->
        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
            });
        </script>
        @endif

        <!-- SweetAlert for notAllowed Message -->
        @if (Session::get('notAllowed'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ Session::get("notAllowed") }}',
            });
        </script>
        @endif
        <div class="card card-form mx-0 my-4" style="border-radius: 15px !important; width: 90%;">
            <div class="card-body px-5 py-4">
                <h3 class="card-heading text-center mt-4">Form Pendaftaran {{ $settingtamyiz->nama_pesantren }}</h3>
                <p class="card-subheading text-center mb-3 font-weight-bold pb-3 text-dark">{{ $settingtamyiz->nama_pesantren }}</p>
                <form method="POST" action="{{route ('store') }}" enctype="multipart/form-data">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @csrf
                    <div class="row">
                        <label for="tanggal_lahir" class="mb-2">Tanggal lahir <span class="required-field text-danger"></span></label>
                        <div class="form-group col-4">
                            <select name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                                <option value="" disabled selected>Tanggal</option>
                                <!-- Generate options for date -->
                                @for ($i = 1; $i <= 31; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <select name="bulan_lahir" id="bulan_lahir" class="form-control" required>
                                <option value="" disabled selected>Bulan</option>
                                <!-- Membuat pilihan bulan -->
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="form-group col-4">
                            <select name="tahun_lahir" id="tahun_lahir" class="form-control" required>
                                <option value="" disabled selected>Tahun</option>
                                <!-- Generate options for year -->
                                @for ($i = date('Y'); $i >= 1900; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Column 1 --}}
                        <div class="col-md-6">
                            {{-- Form groups for the left column --}}
                            <div class="form-group">
                                <label for="nama" class="mb-2">Nama <span class="required-field text-danger"></span></label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap" value="" required autocomplete="nama">
                            </div>
                            <div class="form-group">
                                <label for="telp" class="mb-2">Nomor WhatsApp <span class="required-field text-danger"></span></label>
                                <input type="number" name="phone" id="telp" class="form-control" placeholder="Contoh : 08--------" required>
                            </div>
                            {{-- ... other form groups for the left column ... --}}
                        </div>

                        {{-- Column 2 --}}
                        <div class="col-md-6">
                            {{-- Form groups for the right column --}}
                            <div class="form-group">
                                <label for="jenis_kelamin" class="ml-2 mb-2">Jenis Kelamin <span class="required-field text-danger"></span></label>
                                <select name="jenis_kelamin" class="form-control w-100" id="jenis_kelamin" required>
                                    <option value="" hidden>--Jenis Kelamin--</option>
                                    <option value="P">Perempuan</option>
                                    <option value="L">Laki-Laki</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_program" class="ml-2 mb-2">Jenis Program <span class="required-field text-danger"></span></label>
                                <select name="id_program" class="form-control w-100" id="id_program" required>
                                    <option value="" hidden>--Jenis Program--</option>
                                    <!-- Loop through programs to populate options -->
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id_program }}">{{ $program->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="mb-2">Kota <span class="required-field text-danger"></span></label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="Masukkan Kota" value="" required autocomplete="city">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p class="text-center mt-4"><b>Setelah menekan tombol registrasi, silahkan untuk <br>mendownload PDF sebagai bukti pendaftaran! <br>dan mengecek WhatsApp untuk informasi lebih lanjut!</b></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div></div>
                        <button type="submit" id="btnRegister" class="btn btn-main-sm shadow-md bordered mt-3" style="background-color: #1c2558;"><span style="color:white;">Registrasi</span></button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
    </div>

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Yto15kY1uYq4/3uvTc4U+XfWnE1Zqg8b8z0+2WYr1pdmPq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                theme: 'bootstrap4',
                placeholder: "Please Select"
            });
        });

        function checkvalue2() {
            var sekolah = document.getElementById("sekolah").value;
            var sekolahLainnya = document.getElementById("sekolah_lainnya");
            if (sekolah === "sekolah_lainnya") {
                sekolahLainnya.style.display = 'block';
            } else {
                sekolahLainnya.style.display = 'none';
            }
        }

        $(document).ready(function() {
            // Inisialisasi Select2
            $(".select2").select2({
                theme: 'bootstrap4',
                placeholder: "Please Select"
            });

        });


        $(document).ready(function() {
            $(".select2").select2({
                theme: 'bootstrap4',
                placeholder: "Please Select"
            });

            function checkRequiredFields() {
                var form = $('#registrationForm'); // Assuming your form has an ID 'registrationForm'

                // Check if all required fields are filled
                var isAllFieldsFilled = true;
                form.find('input[required], select[required]').each(function() {
                    if ($(this).val() === '') {
                        isAllFieldsFilled = false;
                        return false; // Exit the loop early if any required field is empty
                    }
                });

                return isAllFieldsFilled;
            }

            // Event listener for the registration button
            $('#btnRegister').click(function(event) {
                var button = $(this);
                var form = button.closest('form'); // Get the form instance

                // Check if the form is valid
                if (form[0].checkValidity() === false) {
                    event.preventDefault(); // Prevent form submission if the form is invalid
                    event.stopPropagation(); // Stop propagation of the event
                }

                // Check if all required fields are filled
                if (!checkRequiredFields()) {
                    event.preventDefault(); // Prevent form submission if there are empty fields
                } else {
                    // Enable the button if all required fields are filled
                    button.prop('disabled', true);
                    form.addClass('was-validated'); // Add Bootstrap validation class
                    $(this).closest('form').submit();
                }
            });

            // Event listener to check and enable/disable the button on input change
            $('input[required], select[required]').on('input change', function() {
                $('#btnRegister').prop('disabled', !checkRequiredFields());
            });
        });
    </script>
</body>
@endforeach

</html>