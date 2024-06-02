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
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NIST</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NAMA</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>NOMOR WHATSAPP</strong></td>
                <td align="center" style="border: 1px solid #000000; background-color: #d9ecd0;"><strong>STATUS PEMBAYARAN</strong></td>
            </tr>
            <?php $no = 0; ?>
            @foreach($data_siswa as $item)
            <?php $no++; ?>
            <tr>
                <td align="center" style="border: 1px solid #000000;">{{ $no }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $item->nist }}</td>
                <td align="center" style="border: 1px solid #000000;">{{ $item->user->nama }}</td>
                @foreach ($data_user as $item2)
                @endforeach
                <td align="center" style="border: 1px solid #000000;">{{ $item2->phone }}</td>
                <td align="center" style="border: 1px solid #000000;">
                    @if(isset($data_pembayaran[$no]))
                    @if($data_pembayaran[$no]->status == 0)
                    <p style="color: red; margin: 0;">Belum Bayar</p>
                    @elseif($data_pembayaran[$no]->status == 1)
                    <p style="color: green; margin: 0;">Diterima</p>
                    @else
                    <p style="color: red; margin: 0;">Ditolak</p>
                    @endif
                    @else
                    <p style="margin: 0;">Belum Ada Data Pembayaran</p>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>