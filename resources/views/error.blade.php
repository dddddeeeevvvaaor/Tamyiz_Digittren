<!DOCTYPE html>
<html lang="en">

<head>
    @foreach ($settingtamyiz as $settingtamyiz)
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settingtamyiz->nama_pesantren }}</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style2.css')}}">


    <!-- bootstrap 5 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css"
        integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body style="background-color: rgb(199, 228, 238)">
<div class="d-flex justify-content-center">
    <div class="">
        <div class="imagetodo d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/img/jaconda-17.gif') }}" alt="" width="600px">
        </div>
        <div class="d-flex justify-content-center text-center">
            <div>
                <p style="font-size: 20px"> <b>Anda tidak diperbolehkan mengakses halaman ini!</b> </p>
                <a href="/dashboard" class="btn btn-primary">Back</a>
        
            </div>
        </div>
    </div>
    @endforeach
</div>

</body>
</html>