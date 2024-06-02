<?php

namespace App\Exports;

use App\Models\Akuntansi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AkuntansiExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');
        $data_akuntansi = Akuntansi::all();
        return view('dashboard.laporanakuntansi.export', compact('time_download', 'data_akuntansi'));
    }
}