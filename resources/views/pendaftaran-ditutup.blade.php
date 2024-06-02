<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    @foreach ($settingtamyiz as $settingtamyiz)
    <title>{{ $settingtamyiz->nama_pesantren }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/'.$settingtamyiz->logo) }}" type="image/x-icon">
</head>

<body class="hold-transition register-page" style="background-color:#37517e;">
    <div class="register-box" style="display: flex; flex-direction: column; justify-content: center; min-height: 100vh;">
        <div class="register-logo">
            <center><a href="/"><img style="width: 150px;" src="{{ asset('assets/images/logo/'.$settingtamyiz->logo) }}"></a></center>
        </div>

        <center>
            <b>
                <!-- <h4><a style="color:white;">KUOTA SUDAH TERCUKUPI</a></h4> -->
            </b>
            <b>
                <!-- <h2><a style="color:#ffcc00;">{{ $settingtamyiz->nama_pesantren }} DI TUTUP</a></h2> -->
                <h2><a style="color:#ffcc00;">Pendaftaran via web sementara ditutup</a></h2>
            </b>
            <b>
                <h2><a style="color:#ffcc00;">Bagi yang ingin mendaftar TAMYIZ DIGITTREN, silahkan menghubungi nomor +62 877-7444-9118.</a></h2>
            </b>
            <b>
                <h2><a style="color:#ffcc00;">Terima kasih</a></h2>
            </b>
            <h4><a style="color:white;">Selalu cek informasi terbaru di :</a></h4>
            <h4><b><a href="{{ $settingtamyiz->website }}" target="_blank" class="nav-link" style="color:white;">WEBSITE</a></b></h4>
            <h4><a style="color:white;">{{ $settingtamyiz->nama_pesantren }}</a></h4>
            @endforeach
            <p class="text-white">.</p>
            <p>
                <a class="btn btn-success" href="/login">Kembali Ke Beranda</a>
            </p>
        </center>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->

    <!-- jQuery 3 -->
    <script src="/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
</body>

</html>