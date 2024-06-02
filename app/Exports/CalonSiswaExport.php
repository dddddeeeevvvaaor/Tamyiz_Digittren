<?php

namespace App\Exports;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class CalonSiswaExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');
        $data_siswa = Siswa::all();
        $data_pembayaran = Payment::all();
        $data_user = User::all();
        return view('dashboard.data_siswa.export', compact('time_download', 'data_siswa', 'data_pembayaran', 'data_user'));
    }
}