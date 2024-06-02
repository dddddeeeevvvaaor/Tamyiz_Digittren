<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santri</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="9"><strong>DATA AKUNTANSI</strong></td>
            </tr>
            <tr>
                <td colspan="9">Waktu download : {{$time_download}}</td>
            </tr>
            <tr>
                <td colspan="9">Didownload oleh : {{Auth::user()->nama}}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
            </tr>
            <tr>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NO</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>PAYMENT ID</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TANGGAL</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>KETERANGAN PROGRAM</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>KETERANGAN INFAQ</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>DEBET</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>SALDO TOTAL</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_akuntansi as $akun)
            <?php $no++; ?>
            <tr>
            <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->id_payment }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->tanggal }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->keterangan_program }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->keterangan_infaq }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->debet }}</td>
            <td align="center" style="border: 1px solid #000000;">{{ $akun->saldo }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6"><strong>Total</strong></td>
                <td><strong>{{ $data_akuntansi->sum('saldo') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>