<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous" />
</head>
<style>
    * {
        font-family: 'Sans-serif';
    }
    
    body {
        margin: 0;
        padding: 0;
        background-color: #fff; /* Ensure a white background for printing */
    }
    
    .header h5, .header h6, .bukti h6, main h6 {
        font-weight: bold;
        font-size: 1rem; /* Use responsive font size */
    }
    
    .header p, main p {
        font-size: 0.875rem; /* Use responsive font size */
        line-height: 1.2; /* Adjust line height for better readability */
    }
    
    main #bab, main #subab {
        background-color: rgb(161, 161, 161);
        margin-top: 20px;
        padding: 5px; /* Add padding for visual space */
        color: white; /* Contrast text color */
    }
    
    .satu table, .dua p, .dua li {
        font-size: 0.75rem; /* Use responsive font size */
    }
    
    .satu table th {
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .satu table {
        width: 100%; /* Ensure table width is responsive */
        border-collapse: collapse; /* Collapse borders for a cleaner look */
    }
    
    .satu table tr {
        line-height: 1.5; /* Improve line height for table rows */
    }
    
    #link {
        color: rgb(55, 44, 218);
        text-decoration: none; /* Optional: remove underline from links */
    }
    
    .back-to-login {
        display: block;
        padding: 10px 20px;
        margin: 20px auto;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center; /* Center text within the button */
        text-decoration: none; /* Remove underline from text */
        width: 80%; /* Set a max-width for mobile devices */
        max-width: 200px; /* Set a max-width for larger screens */
    }
    
    @media print {
        .back-to-login {
            display: none;
        }
    }
</style>

@foreach ($settingtamyiz as $settingtamyiz)

<body>
    <header class="d-flex justify-content-center align-items-center">
        <div class="logo">
            <img src="{{ asset('assets/images/logo/'.$settingtamyiz->logo) }}" alt="" class="" width="85px">
        </div>
        <div class="px-4 header text-center judul">
            <h6>PANITIA PENERIMAAN PESERTA DIDIK BARU</h6>
            <h6>{{ $settingtamyiz->nama_pesantren }}</h6>
            <p>{{ $settingtamyiz->alamat }}</p>
            <p>Email: {{ $settingtamyiz->email }} | Phone: {{ $settingtamyiz->phone }} </p>

        </div>
    </header>
    <hr style="height: 3px; opacity:1;" color="black">
    <div class="bukti text-center">
        <h6><b>TANDA BUKTI PENDAFTARAN</b></h6>
        <H6>{{ $settingtamyiz->nama_pesantren }}</H6>
    </div>
    <main>
        <div class="satu">
            <h6 id="bab">I. BIODATA CALON PESERTA DIDIK</h6>
            <table cellspacing="0" cellpadding="4">
                @foreach ($dataSiswa as $item)

                @endforeach
                <tr>
                    <th width="50%">tanggal daftar</th>
                    <td>:</td>
                    <td>{{ $item['created_at']->format('j F, Y') }}</td>
                </tr>
                @foreach ($user as $user1)
                @endforeach
                <tr>
                    <th width="50%">Nama lengkap</th>
                    <td>:</td>
                    <td>{{ $item['nama'] }}</td>
                </tr>
                <tr>
                    <th width="50%">Nist</th>
                    <td>:</td>
                    <td>{{ $item['nist'] }}</td>
                </tr>
                @foreach ($user as $user)
                @endforeach
                <tr>
                    <th width="50%">no hp</th>
                    <td>:</td>
                    <td>{{ $user['phone'] }}</td>
                </tr>
                @foreach ($programs as $program)
                @endforeach
                <tr>
                    <th width="50%">program</th>
                    <td>:</td>
                    <td>{{ $program['nama'] }}</td>
                </tr>
            </table>
        </div>
        <div class="dua">
            <h6 id="bab">II. INFORMASI DAN PERSYARATAN</h6>
            <h6 id="subab">A. Akun Anda</h6>
            <p>Akses <span id="link">https://programkita.my.id/login</span>, login dengan menggunakan:</p>
            <p>No. HP:{{ $user['phone'] }}</p>
            <p>Untuk password, akan dikirimkan melalui nomor WhatsApp yang telah Anda daftarkan.</p>
            <p>Akun ini digunakan untuk mengecek status pendaftaran pada {{ $settingtamyiz->nama_pesantren }}.</p>
            <h6 id="subab">B. Pembayaran</h6>
            <p>Untuk melakukan pembayaran, silakan login ke akun Anda, lalu klik tombol 'Buat Pembayaran' pada halaman utama.</p>
            <p>Setelah melakukan pembayaran, silakan kembali ke link diatas untuk upload bukti pembayaran.</p>
        </div>
    </main>

    <button class="back-to-login" onclick="window.location.href='{{ route('login') }}'">Melakukan Pembayaran</button>
    <button class="back-to-login" onclick="window.print()">Print</button>


    <script>
        window.print();
    </script>
</body>
@endforeach

</html>