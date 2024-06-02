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
                <td colspan="9"><strong>DATA AKUN BARU USER</strong></td>
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
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>FIRST NAME</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>LAST NAME</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>USERNAME</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>EMAIL</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>ROLE</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>GOLONGAN</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>PAKET</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TANGGAL MULAI</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>TANGGAL BERAKHIR</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>STATUS</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_akunbaruuser as $akunbaruuser)
            <?php $no++; ?>
            <tr>
                <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->id_payment }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->firstname }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->lastname }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->username }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->email }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->role }}</td>
                @foreach($golongan as $gol)
                @if($gol->id_golongan == $akunbaruuser->id_golongan)
                <td align="center" style="border: 1px solid #000000;">{{ $gol->nama_golongan }}</td>
                @endif
                @endforeach
                @foreach($payments as $payment)
                @endforeach
                <td align="center" style="border: 1px solid #000000;">
                    @php
                    if ($payment->jumlah_pembayaran % 12 == 0) {
                    echo ($payment->jumlah_pembayaran / 12) . ' tahun';
                    } else {
                    echo $payment->jumlah_pembayaran . ' bulan';
                    }
                    @endphp
                </td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->mulai }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->berakhir }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $akunbaruuser->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>